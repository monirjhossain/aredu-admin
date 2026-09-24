<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateStudentIntakeAttemptsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('student_intake_attempts')) {
            Schema::create('student_intake_attempts', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('student_id');
                $table->string('intake')->nullable();
                $table->unsignedBigInteger('status_id')->nullable();
                $table->boolean('is_active')->default(true);
                $table->string('outcome', 50)->nullable();
                $table->timestamp('started_at')->nullable();
                $table->timestamp('closed_at')->nullable();
                $table->text('note')->nullable();
                $table->unsignedBigInteger('created_by')->nullable();
                $table->unsignedBigInteger('updated_by')->nullable();
                $table->timestamps();
                $table->softDeletes();

                $table->foreign('student_id')
                    ->references('id')
                    ->on('students')
                    ->onDelete('cascade');

                $table->index(['student_id', 'intake']);
                $table->index(['intake', 'status_id']);
                $table->index(['student_id', 'is_active']);
            });
        }

        if (Schema::hasTable('students') && !Schema::hasColumn('students', 'current_intake_attempt_id')) {
            Schema::table('students', function (Blueprint $table) {
                $table->unsignedBigInteger('current_intake_attempt_id')->nullable()->after('intake');
            });
        }

        $this->backfillAttempts();
    }

    public function down()
    {
        if (Schema::hasTable('students') && Schema::hasColumn('students', 'current_intake_attempt_id')) {
            Schema::table('students', function (Blueprint $table) {
                $table->dropColumn('current_intake_attempt_id');
            });
        }

        Schema::dropIfExists('student_intake_attempts');
    }

    private function backfillAttempts(): void
    {
        if (!Schema::hasTable('students') || !Schema::hasTable('student_intake_attempts')) {
            return;
        }

        $now = now();

        DB::table('students')
            ->whereNull('deleted_at')
            ->orderBy('id')
            ->chunkById(200, function ($students) use ($now) {
                foreach ($students as $student) {
                    $existing = DB::table('student_intake_attempts')
                        ->where('student_id', $student->id)
                        ->where('is_active', 1)
                        ->whereNull('deleted_at')
                        ->first();

                    if ($existing) {
                        if (empty($student->current_intake_attempt_id)) {
                            DB::table('students')
                                ->where('id', $student->id)
                                ->update(['current_intake_attempt_id' => $existing->id]);
                        }
                        continue;
                    }

                    $attemptId = DB::table('student_intake_attempts')->insertGetId([
                        'student_id' => $student->id,
                        'intake' => $student->intake,
                        'status_id' => $student->status,
                        'is_active' => 1,
                        'outcome' => 'in_progress',
                        'started_at' => $student->created_at ?? $now,
                        'closed_at' => null,
                        'note' => null,
                        'created_by' => null,
                        'updated_by' => null,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);

                    DB::table('students')
                        ->where('id', $student->id)
                        ->update(['current_intake_attempt_id' => $attemptId]);
                }
            });
    }
}
