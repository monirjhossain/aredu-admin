<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Campaign extends Model
{
    public const STATUS_ACTIVE = 'active';
    public const STATUS_CLOSED = 'closed';

    protected $fillable = [
        'name',
        'status',
        'note',
        'created_by',
    ];

    protected static function booted()
    {
        static::creating(function (Campaign $campaign) {
            if (empty($campaign->token)) {
                $campaign->token = Str::lower(Str::random(16));
            }
        });
    }

    public function offers()
    {
        return $this->hasMany(CampaignOffer::class)->orderBy('sort_order')->orderBy('id');
    }

    public function claims()
    {
        return $this->hasManyThrough(CampaignClaim::class, CampaignOffer::class);
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
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

    public function remainingSlots(): int
    {
        $offers = $this->relationLoaded('offers') ? $this->offers : $this->offers()->withCount('claims')->get();

        return (int) $offers->sum(function (CampaignOffer $offer) {
            if (!$offer->is_active) {
                return 0;
            }

            return $offer->remainingClaims();
        });
    }
}
