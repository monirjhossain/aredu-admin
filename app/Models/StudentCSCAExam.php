<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Student\Student;

class StudentCSCAExam extends Model
{
    use HasFactory;

    protected $table = 'student_csca_exams';
    protected $fillable = ['student_id', 'csca_exam_id', 'result'];

    public function cscaExam()
    {
        return $this->belongsTo(CSCAExam::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
