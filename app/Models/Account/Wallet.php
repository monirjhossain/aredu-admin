<?php

namespace App\Models\Account;

use App\Models\Currency\Currency;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class Wallet extends Model
{
    protected $fillable = [
        'branch_id',
        'user_id',
        'name',
        'currency_id',
        'balance',
        'is_active'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    public function transactions()
    {
        return $this->hasMany(WalletTransaction::class);
    }

    public function debitCredit($type, float $amount, $reference = null, $description = null)
    {
        if($type == 'income'){
            $this->increment('balance', $amount);
        }else {
            $this->decrement('balance', $amount);
        }

        $this->transactions()->create([
            'wallet_id' => $this->id,
            'type' => $type == 'income' ? 'credit' : 'debit',
            'amount' => $amount,
            'balance_after' => $this->balance,
            'created_by' => Auth::id(),
            'reference' => $reference,
            'description' => $description,
        ]);
    }

    public function transferWithinWallet($type, float $amount, $reference = null, $description = null)
    {
        // DB::transaction(function () use ($from, $to, $amount) {
        //     $from->decrement('balance', $amount);
        //     $to->increment('balance', $amount);

        //     $from->transactions()->create([
        //         'type' => 'transfer',
        //         'amount' => $amount,
        //         'currency' => $from->currency,
        //         'related_wallet_id' => $to->id,
        //     ]);

        //     $to->transactions()->create([
        //         'type' => 'deposit',
        //         'amount' => $amount,
        //         'currency' => $to->currency,
        //         'related_wallet_id' => $from->id,
        //     ]);
        // });
    }
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($student) {
            if (Auth::check() && empty($student->branch_id)) {
                $student->branch_id = Auth::user()->branch;
            }
        });
    }
}


