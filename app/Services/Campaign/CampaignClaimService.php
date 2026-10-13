<?php

namespace App\Services\Campaign;

use App\Models\CampaignClaim;
use App\Models\CampaignOffer;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class CampaignClaimService
{
    public function claim(CampaignOffer $offer, string $name, string $mobile, string $college): CampaignClaim
    {
        $mobile = $this->normalizeMobile($mobile);
        if ($mobile === '') {
            throw new RuntimeException('Mobile number is required.');
        }

        return DB::transaction(function () use ($offer, $name, $mobile, $college) {
            $locked = CampaignOffer::query()->whereKey($offer->id)->lockForUpdate()->firstOrFail();
            $locked->load('campaign');

            $existing = CampaignClaim::query()
                ->where('campaign_offer_id', $locked->id)
                ->where('mobile', $mobile)
                ->first();

            if ($existing) {
                return $existing;
            }

            if (!$locked->is_active || !$locked->campaign || !$locked->campaign->isActive()) {
                throw new RuntimeException('This offer is closed.');
            }

            $taken = CampaignClaim::query()->where('campaign_offer_id', $locked->id)->count();
            if ($taken >= (int) $locked->max_claims) {
                throw new RuntimeException('This offer is full. No more discounts are available on this QR.');
            }

            $serial = $taken + 1;
            $claim = new CampaignClaim([
                'campaign_offer_id' => $locked->id,
                'name' => trim($name),
                'mobile' => $mobile,
                'college_name' => trim($college),
                'claim_code' => $this->makeCode($locked, $serial),
                'discount_amount' => (int) $locked->discount_amount,
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

    private function makeCode(CampaignOffer $offer, int $serial): string
    {
        $thousands = max(1, (int) round(((int) $offer->discount_amount) / 1000));

        return 'C' . $thousands . 'K-' . $offer->id . '-' . str_pad((string) $serial, 4, '0', STR_PAD_LEFT);
    }
}
