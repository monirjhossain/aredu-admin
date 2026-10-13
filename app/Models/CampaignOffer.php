<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CampaignOffer extends Model
{
    protected $fillable = [
        'campaign_id',
        'label',
        'discount_amount',
        'max_claims',
        'token',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'discount_amount' => 'integer',
        'max_claims' => 'integer',
    ];

    protected static function booted()
    {
        static::creating(function (CampaignOffer $offer) {
            if (empty($offer->token)) {
                $offer->token = Str::lower(Str::random(16));
            }
        });
    }

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function claims()
    {
        return $this->hasMany(CampaignClaim::class);
    }

    public function publicUrl(): string
    {
        return url('/campaign/' . $this->token);
    }

    public function qrImageUrl(int $size = 280): string
    {
        return 'https://api.qrserver.com/v1/create-qr-code/?size=' . $size . 'x' . $size
            . '&margin=12&data=' . rawurlencode($this->publicUrl());
    }

    public function displayLabel(): string
    {
        $label = trim((string) $this->label);

        return $label !== '' ? $label : number_format($this->discount_amount) . ' Discount';
    }

    public function remainingClaims(): int
    {
        $used = $this->claims_count ?? $this->claims()->count();

        return max(0, (int) $this->max_claims - (int) $used);
    }
}
