<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampaignClaim extends Model
{
    public const STATUS_AVAILABLE = 'available';
    public const STATUS_USED = 'used';

    protected $fillable = [
        'campaign_offer_id',
        'name',
        'mobile',
        'college_name',
        'claim_code',
        'discount_amount',
        'status',
        'used_at',
        'used_by',
    ];

    protected $casts = [
        'discount_amount' => 'integer',
        'used_at' => 'datetime',
    ];

    public function offer()
    {
        return $this->belongsTo(CampaignOffer::class, 'campaign_offer_id');
    }

    public function isUsed(): bool
    {
        return $this->status === self::STATUS_USED;
    }
}
