<?php

namespace App\Models\Account;

use App\Models\Student\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'branch_id',
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
    public function getFormattedPaidAttribute()
    {
        return number_format($this->paid, 2);
    }
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

    /**
     * Student block embedded in products JSON (legacy / snapshot rows).
     *
     * @return array<string, mixed>
     */
    protected function embeddedStudentFromProducts(): array
    {
        $products = $this->products;
        if (!is_array($products)) {
            return [];
        }

        return isset($products['student']) && is_array($products['student']) ? $products['student'] : [];
    }

    public function displayStudentFullName(): string
    {
        $embedded = $this->embeddedStudentFromProducts();

        return optional($this->student)->full_name ?? ($embedded['full_name'] ?? '');
    }

    public function displayStudentPhone(): string
    {
        $embedded = $this->embeddedStudentFromProducts();

        return optional($this->student)->mobile_no ?? ($embedded['mobile_no'] ?? '');
    }

    public function scopeForStudent(Builder $query, ?int $studentId): Builder
    {
        if (!$studentId) {
            return $query;
        }

        return $query->where('student_id', $studentId);
    }

    public function scopeForBranch(Builder $query, int $branchId): Builder
    {
        return $query->where('branch_id', $branchId);
    }

    public function scopeSearchTerm(Builder $query, ?string $search): Builder
    {
        if (!$search) {
            return $query;
        }

        $search = trim($search);

        return $query->where(function (Builder $innerQuery) use ($search) {
            $innerQuery->where('invoice_no', 'like', '%' . $search . '%')
                ->orWhere('products', 'like', '%' . $search . '%')
                ->orWhereHas('student', function (Builder $studentQuery) use ($search) {
                    $studentQuery->where('first_name', 'like', '%' . $search . '%')
                        ->orWhere('last_name', 'like', '%' . $search . '%')
                        ->orWhere('mobile_no', 'like', '%' . $search . '%')
                        ->orWhere('passport', 'like', '%' . $search . '%');
                })
                ->orWhereHas('user', function (Builder $userQuery) use ($search) {
                    $userQuery->where('name', 'like', '%' . $search . '%');
                });
        });
    }

    public function scopeInvoiceDateBetween(Builder $query, ?string $dateFrom, ?string $dateTo): Builder
    {
        if ($dateFrom) {
            $query->whereDate('invoice_date', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->whereDate('invoice_date', '<=', $dateTo);
        }

        return $query;
    }

    public function scopeCollectedBy(Builder $query, $userId): Builder
    {
        if (!$userId) {
            return $query;
        }

        return $query->where('user_id', $userId);
    }

    public function isStudentFirstInvoice()
    {
        return !Invoice::where('student_id', $this->student_id)
                      ->where('created_at', '<', $this->created_at)
                      ->exists();
    }
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($invoice) {

            // Skip if already set
            if (!empty($invoice->branch_id)) {
                return;
            }

            // Case 1: Invoice for a student
            if (!empty($invoice->student_id)) {
                $student = $invoice->student; // relationship

                if ($student && $student->branch_id) {
                    $invoice->branch_id = $student->branch_id;
                    return;
                }
            }

            // Case 2: Normal invoice (use logged in user)
            if (Auth::check() && Auth::user()->branch_id) {
                $invoice->branch_id = Auth::user()->branch_id;
            }
        });
    }
}
