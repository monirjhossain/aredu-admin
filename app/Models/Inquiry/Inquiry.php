<?php

namespace App\Models\Inquiry;

use App\Models\Branch;
use App\Models\Category;
use App\Models\Student\Student;
use App\Models\Tag;
use App\Models\User;
use App\Traits\ModelSearchAble;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;


class Inquiry extends Model
{
    use HasFactory, ModelSearchAble, SoftDeletes;
    
    protected $table = 'inquiries';
    protected $fillable = [
        'type',
        'branch_id',
        'first_name',
        'last_name',
        'mobile_no',
        'country',
        'country_preferred',
        'course',
        'university',
        'subject',
        'last_education',
        'email',
        'oppurtunity',
        'message',
        'status',
        'view',
        'user_id',
        'student_id',
    ];

    protected $appends = ['full_name'];
    public function getFullNameAttribute() {
        return ucfirst($this->first_name) . ' ' . ucfirst($this->last_name);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function branchInfo()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($inquire) {
            if (Auth::check() && empty($inquire->user_id)) {
                $inquire->user_id = Auth::id();
            }
        });
        static::creating(function ($student) {
            if (Auth::check() && empty($student->branch_id)) {
                $student->branch_id = Auth::user()->branch;
            }
        });
    }
}
