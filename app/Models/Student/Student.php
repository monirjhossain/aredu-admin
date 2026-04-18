<?php

namespace App\Models\Student;

use App\Models\Document;
use App\Models\EducationQualification;
use App\Models\Post\Post;
use App\Models\StudentStatus;
use App\Models\Branch;
use App\Models\Study;
use App\Models\Subject;
use App\Models\UniversityStatus;
use App\Models\User;
use App\Traits\ModelSearchAble;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Student extends Model
{
    use HasFactory, ModelSearchAble, SoftDeletes;

    protected $table = 'students';
    protected $fillable = [
            'branch_id',
            'user_id',
            'study_id',
            'preferred_country',
            'intake',
            'first_name',
            'last_name',
            'passport',
            'email',
            'mobile_no',
            'address',
            'city',
            'country',
            'zip',
            'gender',
            'language_cer',
            'blood',
            'weight',
            'height',
            'marital_status',
            'study_level',
            'last_education',
            'education_level_id',
            'father_name',
            'father_occoupation',
            'father_mobile_no',
            'mother_name',
            'mother_occoupation',
            'mother_mobile_no',
            'ref',
            'resp_person_id',
            'senior_counselor',
            'junior_counselor',
            'note',
            'status'
    ];
    protected $appends = ['full_name'];
    public function getFullNameAttribute() {
        return ucfirst($this->first_name) . ' ' . ucfirst($this->last_name);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function university()
    {
        return $this->belongsTo(Post::class, 'university_id');
    }
    public function responsiblePerson()
    {
        return $this->belongsTo(User::class, 'resp_person_id');
    }
    public function sncounselor()
    {
        return $this->belongsTo(User::class, 'senior_counselor');
    }
    public function jncounselor()
    {
        return $this->belongsTo(User::class, 'junior_counselor');
    }

    public function student_status()
    {
        return $this->belongsTo(StudentStatus::class, 'status');
    }

    public function documents()
    {
        return $this->belongsToMany(Document::class);
    }
    public function universities()
    {
        return $this->belongsToMany(Post::class);
    }
    public function universitiesAppStatus()
    {
        return $this->hasMany(StudentUniversity::class);
    }
    public function universityById($universityId)
    {
        return $this->universitiesAppStatus()
            ->where('university_id', $universityId)
            ->first();
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class);
    }
    // public function studyLevel()
    // {
    //     return $this->hasOne(Study::class, 'id', 'study_level');
    // }
    public function studyLevel()
    {
        // return $this->belongsTo(StudentStatus::class, 'status');
        return $this->belongsTo(Study::class, 'study_id', 'id');
    }
    public function educationQualifications()
    {
        return $this->hasMany(EducationQualification::class, 'student_id', 'id');
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
    public function branchInfo() {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
