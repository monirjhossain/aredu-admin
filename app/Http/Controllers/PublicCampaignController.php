<?php

namespace App\Http\Controllers;

use App\Http\Requests\Campaign\CampaignClaimRequest;
use App\Models\Campaign;
use App\Models\CampaignClaim;
use App\Models\CampaignOffer;
use App\Services\Campaign\CampaignClaimService;
use RuntimeException;

class PublicCampaignController extends Controller
{
    public function show(string $token)
    {
        $campaign = $this->campaign($token);
        if ($campaign->token !== $token) {
            return redirect()->route('campaigns.public.show', $campaign->token);
        }

        return view('frontend.campaigns.form', $this->pageData($campaign));
    }

    public function store(CampaignClaimRequest $request, string $token, CampaignClaimService $claims)
    {
        $campaign = $this->campaign($token);

        try {
            $claim = $claims->claim(
                $campaign,
                (string) $request->input('name'),
                (string) $request->input('mobile'),
                (string) $request->input('college_name')
            );
        } catch (RuntimeException $e) {
            return redirect()
                ->route('campaigns.public.show', $campaign->token)
                ->withInput()
                ->with('error', $e->getMessage());
        }

        return redirect()->route('campaigns.public.claimed', [$campaign->token, $claim->claim_code]);
    }

    public function claimed(string $token, string $code)
    {
        $campaign = $this->campaign($token);
        $claim = $this->claim($campaign, $code);

        return view('frontend.campaigns.form', $this->pageData($campaign, $claim));
    }

    public function pdf(string $token, string $code)
    {
        $campaign = $this->campaign($token);
        $claim = $this->claim($campaign, $code);

        return view('frontend.campaigns.pdf', [
            'campaign' => $campaign,
            'claim' => $claim,
        ]);
    }

    private function pageData(Campaign $campaign, ?CampaignClaim $claim = null): array
    {
        $campaign->load(['offers' => function ($query) {
            $query->withCount('claims');
        }]);

        return [
            'campaign' => $campaign,
            'spots' => $campaign->remainingSlots(),
            'claim' => $claim,
        ];
    }

    private function campaign(string $token): Campaign
    {
        $campaign = Campaign::query()->where('token', $token)->first();
        if ($campaign) {
            return $campaign;
        }

        $offer = CampaignOffer::query()->where('token', $token)->firstOrFail();

        return Campaign::query()->whereKey($offer->campaign_id)->firstOrFail();
    }

    private function claim(Campaign $campaign, string $code): CampaignClaim
    {
        return CampaignClaim::query()
            ->where('campaign_id', $campaign->id)
            ->where('claim_code', $code)
            ->firstOrFail();
    }
}
