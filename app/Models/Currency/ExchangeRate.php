<?php

namespace App\Models\Currency;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExchangeRate extends Model
{
    protected $fillable = ['currency_id', 'rate', 'date'];

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }
}
