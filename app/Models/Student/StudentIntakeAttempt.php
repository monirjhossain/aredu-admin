<?php

namespace App\Models\Student;

use App\Models\StudentStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentIntakeAttempt extends Model
{
    use HasFactory, SoftDeletes;

    public const OUTCOME_IN_PROGRESS = 'in_progress';
    public const OUTCOME_COMPLETED = 'completed';
    public const OUTCOME_FILE_BACK = 'file_back';
    public const OUTCOME_DEFERRED = 'deferred';
    public const OUTCOME_CANCELLED = 'cancelled';

    protected $table = 'student_intake_attempts';

    protected $fillable = [
        'student_id',
        'intake',
        'status_id',
        'is_active',
        'outcome',
        'started_at',
        'closed_at',
        'note',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'started_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function status()
    {
        return $this->belongsTo(StudentStatus::class, 'status_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function notes()
    {
        return $this->hasMany(StudentIntakeAttemptNote::class, 'attempt_id')->orderByDesc('created_at')->orderByDesc('id');
    }

    public static function outcomeLabels(): array
    {
        return [
            self::OUTCOME_IN_PROGRESS => 'In progress',
            self::OUTCOME_COMPLETED => 'Completed',
            self::OUTCOME_FILE_BACK => 'File back',
            self::OUTCOME_DEFERRED => 'Deferred',
            self::OUTCOME_CANCELLED => 'Cancelled',
        ];
    }

    public function getOutcomeLabelAttribute(): string
    {
        $labels = self::outcomeLabels();

        return $labels[$this->outcome] ?? (string) ($this->outcome ?: '—');
    }
}
