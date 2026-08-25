<?php

namespace App\Models\Inquiry;

use App\Models\Branch;
use App\Models\Category;
use App\Models\Student\Student;
use App\Models\Tag;
use App\Models\User;
use App\Services\ApplicationTrackingIdGenerator;
use App\Traits\ModelSearchAble;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;


class Inquiry extends Model
{
    use HasFactory, ModelSearchAble, SoftDeletes;
    
    protected $table = 'inquiries';
    protected $fillable = [
        'tracking_id',
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
        'result',
        'year',
        'csca_subjects',
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
        static::creating(function ($inquiry) {
            if (Auth::check() && empty($inquiry->branch_id)) {
                $inquiry->branch_id = Auth::user()->branch;
            }

            if (empty($inquiry->tracking_id) && !empty($inquiry->branch_id) && Schema::hasColumn('inquiries', 'tracking_id')) {
                $branch = Branch::find($inquiry->branch_id);
                if ($branch) {
                    $inquiry->tracking_id = app(ApplicationTrackingIdGenerator::class)->generate($branch);
                }
            }
        });
    }
}
