<?php

namespace App\Models\Currency;

use App\Models\Account\Wallet;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Currency extends Model
{
    protected $fillable = ['code', 'name', 'symbol', 'is_base'];

    public function exchangeRates(): HasMany
    {
        return $this->hasMany(ExchangeRate::class);
    }
    public function wallets(): HasMany
    {
        return $this->hasMany(Wallet::class);
    }
    public function latestRate()
    {
        return $this->hasOne(ExchangeRate::class)->latestOfMany();
    }
}
