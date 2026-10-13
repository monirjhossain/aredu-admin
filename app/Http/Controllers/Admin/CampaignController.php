<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Campaign\CampaignRequest;
use App\Models\Campaign;
use App\Models\CampaignClaim;
use App\Models\CampaignOffer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CampaignController extends Controller
{
    public function index()
    {
        $campaigns = Campaign::query()
            ->withCount(['offers', 'claims'])
            ->latest('id')
            ->get();

        return view('admin.campaigns.index', [
            'title' => 'Campaigns',
            'campaigns' => $campaigns,
        ]);
    }

    public function create()
    {
        return view('admin.campaigns.form', [
            'title' => 'New Campaign',
            'campaign' => new Campaign(['status' => Campaign::STATUS_ACTIVE]),
            'offers' => $this->defaultOffers(),
        ]);
    }

    public function store(CampaignRequest $request)
    {
        $campaign = DB::transaction(function () use ($request) {
            $campaign = Campaign::create([
                'name' => $request->input('name'),
                'status' => $request->input('status'),
                'note' => $request->input('note'),
                'created_by' => Auth::id(),
            ]);
            $this->syncOffers($campaign, $request->input('offers', []));

            return $campaign;
        });

        return redirect()
            ->route('campaigns.show', $campaign)
            ->with('success', 'Campaign created. Download the QR and share it.');
    }

    public function show(Request $request, Campaign $campaign)
    {
        $campaign->load(['offers' => function ($query) {
            $query->withCount('claims');
        }]);

        $claims = CampaignClaim::query()
            ->with('offer')
            ->whereIn('campaign_offer_id', $campaign->offers->pluck('id'))
            ->when($request->filled('offer'), function ($query) use ($request) {
                $query->where('campaign_offer_id', $request->input('offer'));
            })
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = '%' . $request->input('q') . '%';
                $query->where(function ($inner) use ($term) {
                    $inner->where('name', 'like', $term)
                        ->orWhere('mobile', 'like', $term)
                        ->orWhere('college_name', 'like', $term)
                        ->orWhere('claim_code', 'like', $term);
                });
            })
            ->latest('id')
            ->paginate(20)
            ->withQueryString();

        return view('admin.campaigns.show', [
            'title' => $campaign->name,
            'campaign' => $campaign,
            'claims' => $claims,
        ]);
    }

    public function edit(Campaign $campaign)
    {
        $campaign->load('offers');

        return view('admin.campaigns.form', [
            'title' => 'Edit Campaign',
            'campaign' => $campaign,
            'offers' => $campaign->offers,
        ]);
    }

    public function update(CampaignRequest $request, Campaign $campaign)
    {
        DB::transaction(function () use ($request, $campaign) {
            $campaign->update([
                'name' => $request->input('name'),
                'status' => $request->input('status'),
                'note' => $request->input('note'),
            ]);
            $this->syncOffers($campaign, $request->input('offers', []));
        });

        return redirect()
            ->route('campaigns.show', $campaign)
            ->with('success', 'Campaign updated.');
    }

    public function markClaim(Campaign $campaign, CampaignClaim $claim)
    {
        abort_unless((int) optional($claim->offer)->campaign_id === (int) $campaign->id, 404);

        if ($claim->isUsed()) {
            $claim->forceFill([
                'status' => CampaignClaim::STATUS_AVAILABLE,
                'used_at' => null,
                'used_by' => null,
            ])->save();
            $message = 'Claim marked as available again.';
        } else {
            $claim->forceFill([
                'status' => CampaignClaim::STATUS_USED,
                'used_at' => now(),
                'used_by' => Auth::id(),
            ])->save();
            $message = 'Discount marked as used on the file opening charge.';
        }

        return redirect()->back()->with('success', $message);
    }

    private function syncOffers(Campaign $campaign, array $rows): void
    {
        $keptIds = [];
        foreach (array_values($rows) as $index => $row) {
            $payload = [
                'label' => trim((string) ($row['label'] ?? '')),
                'discount_amount' => (int) $row['discount_amount'],
                'max_claims' => (int) $row['max_claims'],
                'is_active' => !empty($row['is_active']),
                'sort_order' => $index,
            ];

            $existingId = (int) ($row['id'] ?? 0);
            if ($existingId > 0) {
                $offer = $campaign->offers()->whereKey($existingId)->first();
                if (!$offer) {
                    continue;
                }
                $claimed = $offer->claims()->count();
                if ($payload['max_claims'] < $claimed) {
                    $payload['max_claims'] = $claimed;
                }
                $offer->update($payload);
                $keptIds[] = $offer->id;
                continue;
            }

            $offer = $campaign->offers()->create($payload);
            $keptIds[] = $offer->id;
        }

        $campaign->offers()
            ->whereNotIn('id', $keptIds)
            ->whereDoesntHave('claims')
            ->delete();
    }

    private function defaultOffers(): array
    {
        return [
            ['label' => '5,000 Discount', 'discount_amount' => 5000, 'max_claims' => 300, 'is_active' => true],
            ['label' => '10,000 Discount', 'discount_amount' => 10000, 'max_claims' => 150, 'is_active' => true],
            ['label' => '15,000 Discount', 'discount_amount' => 15000, 'max_claims' => 40, 'is_active' => true],
            ['label' => '20,000 Discount', 'discount_amount' => 20000, 'max_claims' => 10, 'is_active' => true],
        ];
    }
}
