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

class UniversityApplicationStatus extends Model
{
    use HasFactory, ModelSearchAble;

    protected $table = 'university_application_status';
    protected $fillable = [
        'student_university_id',
        'status_id',
        'updated_by',
        'active'	          
    ];

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
}
