<?php

namespace App\Models\Student;

use App\Models\Document;
use App\Models\EducationQualification;
use App\Models\Post\Post;
use App\Models\StudentStatus;
use App\Models\Study;
use App\Models\Subject;
use App\Models\UniversityStatus;
use App\Models\User;
use App\Traits\ModelSearchAble;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentUniversity extends Model
{
    use HasFactory, ModelSearchAble;

    protected $table = 'student_university';
    protected $fillable = [
        'application_no',
        'student_id',
        'university_id',
        'subject_id',
        'status_id',
        'login_id',
        'login_password',
        'agent_portal',
        'admission_notice',
        'jw_copy',
        'visa_copy',
        'updated_by',
        'note',
        'remarks'	          
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    public function university()
    {
        return $this->belongsTo(Post::class, 'university_id');
    }
    public function universities()
    {
        return $this->belongsToMany(Post::class);
    }
    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }
    public function statusUni()
    {
        return $this->belongsTo(UniversityStatus::class, 'status_id');
    }
    public function statusAll()
    {
        return $this->hasMany(UniversityApplicationStatus::class, 'student_university_id', 'id');
    } 
}
