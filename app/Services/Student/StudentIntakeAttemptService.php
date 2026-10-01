<?php

namespace App\Services\Student;

use App\Models\Student\Student;
use App\Models\Student\StudentIntakeAttempt;
use App\Models\Student\StudentIntakeAttemptNote;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class StudentIntakeAttemptService
{
    public const DEFAULT_START_STATUS_ID = 1;

    /**
     * Ensure the student has an active intake attempt (create first if missing).
     */
    public function ensureInitialAttempt(Student $student): StudentIntakeAttempt
    {
        $existing = $student->currentIntakeAttempt
            ?? $student->intakeAttempts()->where('is_active', true)->latest('id')->first();

        if ($existing) {
            if ((int) $student->current_intake_attempt_id !== (int) $existing->id) {
                $student->forceFill(['current_intake_attempt_id' => $existing->id])->save();
            }

            return $existing;
        }

        return $this->createActiveAttempt(
            $student,
            (string) ($student->intake ?: date('Y')),
            $student->status ? (int) $student->status : self::DEFAULT_START_STATUS_ID,
            StudentIntakeAttempt::OUTCOME_IN_PROGRESS,
            null
        );
    }

    /**
     * Mirror office status onto the active attempt (Phase 1 compatibility).
     */
    public function syncStatusToActiveAttempt(Student $student, int $statusId): void
    {
        $attempt = $this->ensureInitialAttempt($student);
        $attempt->forceFill([
            'status_id' => $statusId,
            'updated_by' => Auth::id(),
        ])->save();
    }

    /**
     * Same-intake correction: update active attempt intake without closing it.
     */
    public function syncIntakeToActiveAttempt(Student $student, ?string $intake): void
    {
        $attempt = $this->ensureInitialAttempt($student);
        $attempt->forceFill([
            'intake' => $intake,
            'updated_by' => Auth::id(),
        ])->save();
    }

    /**
     * Close current attempt and open a new one for the next intake.
     *
     * @param  array{new_intake: string, outcome?: string, note?: string|null, start_status_id?: int|null}  $input
     */
    public function carryOver(Student $student, array $input): StudentIntakeAttempt
    {
        $newIntake = trim((string) ($input['new_intake'] ?? ''));
        if ($newIntake === '') {
            throw new InvalidArgumentException('New intake is required.');
        }

        $outcome = $input['outcome'] ?? StudentIntakeAttempt::OUTCOME_DEFERRED;
        $allowed = array_keys(StudentIntakeAttempt::outcomeLabels());
        if (!in_array($outcome, $allowed, true) || $outcome === StudentIntakeAttempt::OUTCOME_IN_PROGRESS) {
            $outcome = StudentIntakeAttempt::OUTCOME_DEFERRED;
        }

        $note = isset($input['note']) ? trim((string) $input['note']) : null;
        $newFileId = isset($input['new_file_student_id'])
            ? trim((string) $input['new_file_student_id'])
            : '';
        $startStatusId = isset($input['start_status_id'])
            ? (int) $input['start_status_id']
            : self::DEFAULT_START_STATUS_ID;

        return DB::transaction(function () use ($student, $newIntake, $outcome, $note, $newFileId, $startStatusId) {
            $student = Student::query()->lockForUpdate()->findOrFail($student->id);
            $current = $this->ensureInitialAttempt($student);

            if ((string) $current->intake === $newIntake && $current->is_active) {
                throw new InvalidArgumentException('New intake must be different from the current intake.');
            }

            $oldFileId = trim((string) ($student->file_student_id ?? ''));
            $fileIdChanged = $newFileId !== '' && $newFileId !== $oldFileId;
            if ($fileIdChanged) {
                $taken = Student::withTrashed()
                    ->where('file_student_id', $newFileId)
                    ->where('id', '!=', $student->id)
                    ->exists();
                if ($taken) {
                    throw new InvalidArgumentException('This File ID is already used.');
                }
            }

            $current->forceFill([
                'is_active' => false,
                'closed_at' => now(),
                'outcome' => $outcome,
                'updated_by' => Auth::id(),
            ])->save();

            if ($note !== null && $note !== '') {
                $this->addNote($current, $note);
            }
            if ($fileIdChanged) {
                $this->addNote($current, 'File ID changed from ' . ($oldFileId !== '' ? $oldFileId : '—') . ' to ' . $newFileId);
            }

            $newAttempt = $this->createActiveAttempt(
                $student,
                $newIntake,
                $startStatusId,
                StudentIntakeAttempt::OUTCOME_IN_PROGRESS,
                null
            );

            $student->forceFill([
                'intake' => $newIntake,
                'status' => $startStatusId,
                'current_intake_attempt_id' => $newAttempt->id,
                'file_student_id' => $fileIdChanged ? $newFileId : $student->file_student_id,
            ])->save();

            return $newAttempt;
        });
    }

    public function addNote(StudentIntakeAttempt $attempt, string $body): StudentIntakeAttemptNote
    {
        $body = trim($body);
        if ($body === '') {
            throw new InvalidArgumentException('Note cannot be empty.');
        }

        $note = new StudentIntakeAttemptNote([
            'attempt_id' => $attempt->id,
            'body' => $body,
            'created_by' => Auth::id(),
        ]);
        $note->save();

        $attempt->forceFill([
            'note' => trim((string) ($attempt->note ? $attempt->note . "\n" : '') . $body),
            'updated_by' => Auth::id(),
        ])->save();

        return $note;
    }

    private function createActiveAttempt(
        Student $student,
        string $intake,
        int $statusId,
        string $outcome,
        ?string $note
    ): StudentIntakeAttempt {
        $attempt = new StudentIntakeAttempt([
            'student_id' => $student->id,
            'intake' => $intake,
            'status_id' => $statusId,
            'is_active' => true,
            'outcome' => $outcome,
            'started_at' => now(),
            'closed_at' => null,
            'note' => $note,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);
        $attempt->save();

        $student->forceFill([
            'current_intake_attempt_id' => $attempt->id,
        ])->save();

        return $attempt;
    }
}
