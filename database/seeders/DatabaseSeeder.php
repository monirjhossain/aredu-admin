<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(1)->create();
        $this->call([
            PostsTableSeeder::class,
            RolesTableSeeder::class,
            DocumentsTableSeeder::class,
            ExpensesTableSeeder::class,
            ProductsTableSeeder::class,

            SmsTemplatesTableSeeder::class,
            StudentStatusesTableSeeder::class,
            // StudentSubjectTableSeeder::class,
            StudiesTableSeeder::class,
            SubjectsTableSeeder::class,
            
            UniversityStatusesTableSeeder::class,
            
            PostSubjectTableSeeder::class,
            PostTagTableSeeder::class,
            // PostStudentTableSeeder::class,
            
            CategoriesTableSeeder::class,
            CategoryPostTableSeeder::class,
            TagsTableSeeder::class,
            
            UsersTableSeeder::class,
            BranchesTableSeeder::class,
            EducationLevelsTableSeeder::class,

            CurrencySeeder::class,
            WalletSeeder::class

        ]);
    }
}
