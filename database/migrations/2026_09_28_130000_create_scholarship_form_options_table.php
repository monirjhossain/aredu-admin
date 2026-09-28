<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateScholarshipFormOptionsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('scholarship_form_options')) {
            Schema::create('scholarship_form_options', function (Blueprint $table) {
                $table->id();
                $table->string('group', 40);
                $table->string('label', 120);
                $table->unsignedSmallInteger('sort')->default(0);
                $table->timestamps();
                $table->unique(['group', 'label']);
            });
        }

        $rows = [];
        $groups = [
            'coverage' => ['Full', 'Partial', 'Full tuition', 'Varies'],
            'degree' => ['Bachelor', 'Master', 'PhD', 'MBBS', 'Diploma', 'Foundation', 'Language'],
            'language' => ['English', 'Chinese', 'Bilingual'],
            'scholarship_type' => ['Type A', 'Type B', 'Type C', 'Type D'],
        ];
        $year = (int) date('Y');
        for ($i = -1; $i <= 3; $i++) {
            $groups['intake'][] = 'March-' . ($year + $i);
            $groups['intake'][] = 'September-' . ($year + $i);
        }

        foreach ($groups as $group => $labels) {
            foreach (array_values($labels) as $index => $label) {
                $rows[] = [
                    'group' => $group,
                    'label' => $label,
                    'sort' => $index + 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        foreach ($rows as $row) {
            DB::table('scholarship_form_options')->updateOrInsert(
                ['group' => $row['group'], 'label' => $row['label']],
                $row
            );
        }
    }

    public function down()
    {
        Schema::dropIfExists('scholarship_form_options');
    }
}
