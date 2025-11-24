<?php

namespace App\Models\Account;

use App\Models\Student\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'invoice_no',
        'student_id',
        'invoice_date',
        'due_date',
        'products',
        'documents',
        'subtotal',
        'discount',
        'tax',
        'grandtotal',
        'paid',
        'due',
        'user_id',
        'is_refundable',
        'wallet_id'
    ];
    protected $casts = [
        'products' => 'array',
        'documents' => 'array',
        'is_refundable' => 'boolean',
    ];
    public function setIsRefundableAttribute($value)
    {
        $this->attributes['is_refundable'] = $value === 'on' ? 1 : 0;
    }

    public function student()
    {
        return $this->belongsTo(student::class, 'student_id');
    }
    public function wallet()
    {
        return $this->belongsTo(Wallet::class, 'wallet_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
        public function isStudentFirstInvoice()
    {
        return !Invoice::where('student_id', $this->student_id)
                      ->where('created_at', '<', $this->created_at)
                      ->exists();
    }
}
