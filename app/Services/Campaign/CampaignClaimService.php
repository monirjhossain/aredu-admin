<?php

namespace App\Services\Campaign;

use App\Models\Campaign;
use App\Models\CampaignClaim;
use App\Models\CampaignOffer;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class CampaignClaimService
{
    public function claim(Campaign $campaign, string $name, string $mobile, string $college): CampaignClaim
    {
        $mobile = $this->normalizeMobile($mobile);
        if ($mobile === '') {
            throw new RuntimeException('Mobile number is required.');
        }

        return DB::transaction(function () use ($campaign, $name, $mobile, $college) {
            $locked = Campaign::query()->whereKey($campaign->id)->lockForUpdate()->firstOrFail();

            $existing = CampaignClaim::query()
                ->where('campaign_id', $locked->id)
                ->where('mobile', $mobile)
                ->first();

            if ($existing) {
                return $existing;
            }

            if (!$locked->isActive()) {
                throw new RuntimeException('This offer is closed.');
            }

            $offers = CampaignOffer::query()
                ->where('campaign_id', $locked->id)
                ->orderBy('sort_order')
                ->orderBy('id')
                ->lockForUpdate()
                ->get();

            $winner = $this->drawOffer($offers);
            if (!$winner) {
                throw new RuntimeException('This QR is full. No more discounts are available.');
            }

            $taken = CampaignClaim::query()->where('campaign_offer_id', $winner->id)->count();
            $claim = new CampaignClaim([
                'campaign_id' => $locked->id,
                'campaign_offer_id' => $winner->id,
                'name' => trim($name),
                'mobile' => $mobile,
                'college_name' => trim($college),
                'claim_code' => $this->makeCode($winner, $taken + 1),
                'discount_amount' => (int) $winner->discount_amount,
                'status' => CampaignClaim::STATUS_AVAILABLE,
            ]);
            $claim->save();

            return $claim;
        });
    }

    public function normalizeMobile(string $mobile): string
    {
        return preg_replace('/\D+/', '', $mobile) ?? '';
    }

    /**
     * @param \Illuminate\Support\Collection<int, CampaignOffer> $offers
     */
    private function drawOffer($offers): ?CampaignOffer
    {
        $buckets = [];
        $total = 0;

        foreach ($offers as $offer) {
            if (!$offer->is_active) {
                continue;
            }

            $taken = CampaignClaim::query()->where('campaign_offer_id', $offer->id)->count();
            $left = (int) $offer->max_claims - $taken;
            if ($left < 1) {
                continue;
            }

            $buckets[] = ['offer' => $offer, 'left' => $left];
            $total += $left;
        }

        if ($total < 1) {
            return null;
        }

        $roll = random_int(1, $total);
        $cursor = 0;
        foreach ($buckets as $bucket) {
            $cursor += $bucket['left'];
            if ($roll <= $cursor) {
                return $bucket['offer'];
            }
        }

        return null;
    }

    private function makeCode(CampaignOffer $offer, int $serial): string
    {
        $thousands = max(1, (int) round(((int) $offer->discount_amount) / 1000));

        return 'C' . $thousands . 'K-' . $offer->id . '-' . str_pad((string) $serial, 4, '0', STR_PAD_LEFT);
    }
}
