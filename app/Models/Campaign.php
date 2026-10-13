<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
