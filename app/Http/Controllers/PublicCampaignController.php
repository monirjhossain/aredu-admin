<?php

namespace App\Http\Controllers;

use App\Http\Requests\Campaign\CampaignClaimRequest;
use App\Models\CampaignClaim;
use App\Models\CampaignOffer;
use App\Services\Campaign\CampaignClaimService;
use App\Support\PdfGenerator;
use RuntimeException;

class PublicCampaignController extends Controller
{
    public function show(string $token)
    {
        $offer = $this->offer($token);
        $offer->loadCount('claims');

        return view('frontend.campaigns.form', [
            'offer' => $offer,
            'campaign' => $offer->campaign,
            'full' => $offer->remainingClaims() < 1,
            'claim' => null,
        ]);
    }

    public function store(CampaignClaimRequest $request, string $token, CampaignClaimService $claims)
    {
        $offer = $this->offer($token);

        try {
            $claim = $claims->claim(
                $offer,
                (string) $request->input('name'),
                (string) $request->input('mobile'),
                (string) $request->input('college_name')
            );
        } catch (RuntimeException $e) {
            return redirect()
                ->route('campaigns.public.show', $token)
                ->withInput()
                ->with('error', $e->getMessage());
        }

        return redirect()->route('campaigns.public.claimed', [$token, $claim->claim_code]);
    }

    public function claimed(string $token, string $code)
    {
        $offer = $this->offer($token);
        $claim = CampaignClaim::query()
            ->where('campaign_offer_id', $offer->id)
            ->where('claim_code', $code)
            ->firstOrFail();

        return view('frontend.campaigns.form', [
            'offer' => $offer->loadCount('claims'),
            'campaign' => $offer->campaign,
            'full' => $offer->remainingClaims() < 1,
            'claim' => $claim,
        ]);
    }

    public function pdf(string $token, string $code, PdfGenerator $pdfs)
    {
        $offer = $this->offer($token);
        $claim = CampaignClaim::query()
            ->where('campaign_offer_id', $offer->id)
            ->where('claim_code', $code)
            ->firstOrFail();

        $html = view('frontend.campaigns.pdf', [
            'offer' => $offer,
            'campaign' => $offer->campaign,
            'claim' => $claim,
            'logo' => str_replace('\\', '/', public_path('media/custom/logo.png')),
        ])->render();

        return $pdfs->download($html, 'AR-Education-' . $claim->claim_code . '.pdf', [
            'format' => 'A5',
            'margin_left' => 12,
            'margin_right' => 12,
            'margin_top' => 12,
            'margin_bottom' => 12,
        ]);
    }

    private function offer(string $token): CampaignOffer
    {
        return CampaignOffer::query()
            ->with('campaign')
            ->where('token', $token)
            ->firstOrFail();
    }
}
