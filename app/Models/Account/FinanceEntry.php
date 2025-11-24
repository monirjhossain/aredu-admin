<?php

namespace App\Models\Account;

use App\Models\Expense;
use App\Models\Product;
use App\Models\Student\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FinanceEntry extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'type',
        'amount',
        'transaction_date',
        'student_id',
        'created_by',
        'description',
        'reference',
        'categories',
        'category_id',
        'currency',
        'exchange_rate',
        'base_amount'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function expense()
    {
        return $this->belongsTo(Expense::class, 'category_id');
    }
    public function income()
    {
        return $this->belongsTo(Product::class, 'category_id');
    }
    protected $casts = [
        'categories' => 'array',
    ];
}