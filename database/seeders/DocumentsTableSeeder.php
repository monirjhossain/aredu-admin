<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DocumentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $documents = [
            ['name' => 'Passport'],
            ['name' => 'Student Photo'],
            ['name' => 'Police Clearance'],
            ['name' => 'Student NID'],
            ['name' => 'Medical'],

            ['name' => 'Masters Certificate'],
            ['name' => 'Masters Marksheet'],
            ['name' => 'Masters Recommendation letter'],

            ['name' => 'BSC Certificate'],
            ['name' => 'BSC Marksheet'],
            ['name' => 'BSC Recommendation letter'],
            ['name' => 'Research Paper / Thesis paper'],
            ['name' => 'Publication'],

            ['name' => 'HSC Certificate'],
            ['name' => 'HSC Marksheet'],
            ['name' => 'HSC Testimonial'],
            ['name' => 'HSC Recommendation letter'],

            
            ['name' => 'Diploma Certificate'],
            ['name' => 'Diploma Marksheet ____ to ____ Semister'],
            ['name' => 'Diploma Testimonial'],
            ['name' => 'Diploma Recommendation letter'],
            
            ['name' => 'SSC Certificate'],
            ['name' => 'SSC Marksheet'],
            ['name' => 'SSC Testimonial'],
            ['name' => 'SSC Recommendation letter'],
            
            ['name' => 'A Level Certificate'],
            ['name' => 'A Level Marksheet'],
            ['name' => 'A Level Testimonial'],
            ['name' => 'A Level Recommendation letter'],

            ['name' => 'O Level Certificate'],
            ['name' => 'O Level Marksheet'],
            ['name' => 'O Level Testimonial'],
            ['name' => 'O Level Recommendation letter'],
            
            ['name' => 'English Proficiency Certificate'],
            ['name' => 'MOI'],
            ['name' => 'IELTS'],
            ['name' => 'Duolingo'],
            ['name' => 'TOEFL'],
            ['name' => 'EF SET Certificate'],

            ['name' => 'Study plan'],
            ['name' => 'Student CV'],
            ['name' => 'Student information'],
            ['name' => 'Introduction video'],
            
            ['name' => 'Job/Experience Certificate'],
            ['name' => 'Gap certificate'],
            ['name' => 'Extra certificate'],
            ['name' => 'Father NID'],
            ['name' => 'Mother NID'],
            ['name' => 'Hand written sign student/father'],
            ['name' => 'Bank Statement'],
            ['name' => 'Bank Solvency'],
        ];
        DB::table('documents')->insert($documents);
    }
}
