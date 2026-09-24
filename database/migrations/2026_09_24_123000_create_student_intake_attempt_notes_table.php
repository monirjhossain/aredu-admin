<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateStudentIntakeAttemptNotesTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('student_intake_attempt_notes')) {
            Schema::create('student_intake_attempt_notes', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('attempt_id');
                $table->text('body');
                $table->unsignedBigInteger('created_by')->nullable();
                $table->timestamps();

                $table->foreign('attempt_id')
                    ->references('id')
                    ->on('student_intake_attempts')
                    ->onDelete('cascade');

                $table->index(['attempt_id', 'created_at']);
            });
        }

        $this->backfillFromAttemptNotes();
    }

    public function down()
    {
        Schema::dropIfExists('student_intake_attempt_notes');
    }

    private function backfillFromAttemptNotes(): void
    {
        if (!Schema::hasTable('student_intake_attempts') || !Schema::hasTable('student_intake_attempt_notes')) {
            return;
        }

        $now = now();

        DB::table('student_intake_attempts')
            ->whereNotNull('note')
            ->where('note', '!=', '')
            ->orderBy('id')
            ->chunkById(100, function ($attempts) use ($now) {
                foreach ($attempts as $attempt) {
                    $exists = DB::table('student_intake_attempt_notes')
                        ->where('attempt_id', $attempt->id)
                        ->exists();

                    if ($exists) {
                        continue;
                    }

                    DB::table('student_intake_attempt_notes')->insert([
                        'attempt_id' => $attempt->id,
                        'body' => $attempt->note,
                        'created_by' => $attempt->updated_by ?: $attempt->created_by,
                        'created_at' => $attempt->updated_at ?: ($attempt->created_at ?: $now),
                        'updated_at' => $now,
                    ]);
                }
            });
    }
}
