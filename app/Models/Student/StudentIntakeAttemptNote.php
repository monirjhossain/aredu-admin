<?php

namespace App\Models\Student;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentIntakeAttemptNote extends Model
{
    use HasFactory;

    protected $table = 'student_intake_attempt_notes';

    protected $fillable = [
        'attempt_id',
        'body',
        'created_by',
    ];

    public function attempt()
    {
        return $this->belongsTo(StudentIntakeAttempt::class, 'attempt_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
