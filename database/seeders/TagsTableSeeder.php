<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TagsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tags')->insert(array (
  0 => 
  array (
    'id' => 2,
    'name' => 'Pharmacy',
    'slug' => 'Pharmacy',
    'image' => NULL,
    'description' => NULL,
    'created_at' => '2023-05-13 23:16:05',
    'updated_at' => '2023-05-13 23:16:05',
  ),
  1 => 
  array (
    'id' => 3,
    'name' => 'BSC (Bachelor of Science)',
    'slug' => 'BSC-(Bachelor-of-Science)',
    'image' => NULL,
    'description' => NULL,
    'created_at' => '2023-05-13 23:16:46',
    'updated_at' => '2023-05-13 23:16:46',
  ),
  2 => 
  array (
    'id' => 4,
    'name' => 'MBA (Master of Business Administration)',
    'slug' => 'MBA-(Master-of-Business-Administration)',
    'image' => NULL,
    'description' => NULL,
    'created_at' => '2023-05-13 23:17:14',
    'updated_at' => '2023-05-13 23:17:14',
  ),
  3 => 
  array (
    'id' => 5,
    'name' => 'BBA (Bachelor of Business Administration)',
    'slug' => 'BBA-(Bachelor-of-Business-Administration)',
    'image' => NULL,
    'description' => NULL,
    'created_at' => '2023-05-13 23:18:24',
    'updated_at' => '2023-05-13 23:18:24',
  ),
  4 => 
  array (
    'id' => 6,
    'name' => 'MSC (Masters of Sciences)',
    'slug' => 'MSC-(Masters-of-Sciences)',
    'image' => NULL,
    'description' => NULL,
    'created_at' => '2023-05-13 23:19:13',
    'updated_at' => '2023-05-13 23:19:13',
  ),
  5 => 
  array (
    'id' => 7,
    'name' => 'MBBS (Bachelor of Medicine and Bachelor of Surgery)',
    'slug' => 'MBBS-(Bachelor-of-Medicine-and-Bachelor-of-Surgery)',
    'image' => NULL,
    'description' => NULL,
    'created_at' => '2023-05-13 23:20:02',
    'updated_at' => '2023-05-13 23:20:02',
  ),
  6 => 
  array (
    'id' => 8,
    'name' => 'Diploma',
    'slug' => 'Diploma',
    'image' => NULL,
    'description' => NULL,
    'created_at' => '2023-05-13 23:21:05',
    'updated_at' => '2023-05-13 23:21:05',
  ),
  7 => 
  array (
    'id' => 9,
    'name' => 'international business and economics (IBE)',
    'slug' => 'international-business-and-economics-(IBE)',
    'image' => NULL,
    'description' => NULL,
    'created_at' => '2023-05-20 23:47:18',
    'updated_at' => '2023-05-20 23:53:05',
  ),
  8 => 
  array (
    'id' => 10,
    'name' => 'AI',
    'slug' => 'AI',
    'image' => NULL,
    'description' => NULL,
    'created_at' => '2023-05-20 23:49:08',
    'updated_at' => '2023-05-20 23:49:08',
  ),
  9 => 
  array (
    'id' => 11,
    'name' => 'Computer Science And Technology',
    'slug' => 'Computer-Science-And-Technology',
    'image' => NULL,
    'description' => NULL,
    'created_at' => '2023-05-24 03:23:09',
    'updated_at' => '2023-05-24 03:23:09',
  ),
  10 => 
  array (
    'id' => 12,
    'name' => 'Software Engineering',
    'slug' => 'Software-Engineering',
    'image' => NULL,
    'description' => NULL,
    'created_at' => '2023-05-24 03:23:40',
    'updated_at' => '2023-05-24 03:23:40',
  ),
  11 => 
  array (
    'id' => 13,
    'name' => 'IOT ( Internet of Things) Engineering',
    'slug' => 'IOT-(-Internet-of-Things)-Engineering',
    'image' => NULL,
    'description' => NULL,
    'created_at' => '2023-05-24 03:24:18',
    'updated_at' => '2023-05-24 03:24:18',
  ),
  12 => 
  array (
    'id' => 14,
    'name' => 'Data Science and Bio Data Technology',
    'slug' => 'Data-Science-and-Bio-Data-Technology',
    'image' => NULL,
    'description' => NULL,
    'created_at' => '2023-05-24 03:24:51',
    'updated_at' => '2023-05-24 03:24:51',
  ),
  13 => 
  array (
    'id' => 15,
    'name' => 'Electrical Engineering and Automation',
    'slug' => 'Electrical-Engineering-and-Automation',
    'image' => NULL,
    'description' => NULL,
    'created_at' => '2023-05-24 03:25:19',
    'updated_at' => '2023-05-24 03:25:19',
  ),
  14 => 
  array (
    'id' => 16,
    'name' => 'Electronics Science and Technology',
    'slug' => 'Electronics-Science-and-Technology',
    'image' => NULL,
    'description' => NULL,
    'created_at' => '2023-05-24 03:25:43',
    'updated_at' => '2023-05-24 03:25:43',
  ),
  15 => 
  array (
    'id' => 17,
    'name' => 'Artificial Intelligence',
    'slug' => 'Artificial-Intelligence',
    'image' => NULL,
    'description' => NULL,
    'created_at' => '2023-05-24 03:26:25',
    'updated_at' => '2023-05-24 03:26:25',
  ),
  16 => 
  array (
    'id' => 18,
    'name' => 'Robot Engineering',
    'slug' => 'Robot-Engineering',
    'image' => NULL,
    'description' => NULL,
    'created_at' => '2023-05-24 03:26:41',
    'updated_at' => '2023-05-24 03:26:41',
  ),
  17 => 
  array (
    'id' => 19,
    'name' => 'Industrial Design',
    'slug' => 'Industrial-Design',
    'image' => NULL,
    'description' => NULL,
    'created_at' => '2023-05-24 03:26:53',
    'updated_at' => '2023-05-24 03:26:53',
  ),
  18 => 
  array (
    'id' => 20,
    'name' => 'Eco - Engineering of Environment',
    'slug' => 'Eco---Engineering-of-Environment',
    'image' => NULL,
    'description' => NULL,
    'created_at' => '2023-05-24 03:27:16',
    'updated_at' => '2023-05-24 03:27:16',
  ),
  19 => 
  array (
    'id' => 21,
    'name' => 'Business Administration',
    'slug' => 'Business-Administration',
    'image' => NULL,
    'description' => NULL,
    'created_at' => '2023-05-24 03:27:31',
    'updated_at' => '2023-05-24 03:27:31',
  ),
  20 => 
  array (
    'id' => 22,
    'name' => 'Accounting',
    'slug' => 'Accounting',
    'image' => NULL,
    'description' => NULL,
    'created_at' => '2023-05-24 03:27:37',
    'updated_at' => '2023-05-24 03:27:37',
  ),
  21 => 
  array (
    'id' => 23,
    'name' => 'Economics',
    'slug' => 'Economics',
    'image' => NULL,
    'description' => NULL,
    'created_at' => '2023-05-24 03:27:47',
    'updated_at' => '2023-05-24 03:27:47',
  ),
  22 => 
  array (
    'id' => 24,
    'name' => 'Digital Economy',
    'slug' => 'Digital-Economy',
    'image' => NULL,
    'description' => NULL,
    'created_at' => '2023-05-24 03:28:00',
    'updated_at' => '2023-05-24 03:28:00',
  ),
  23 => 
  array (
    'id' => 25,
    'name' => 'Integrated Science',
    'slug' => 'Integrated-Science',
    'image' => NULL,
    'description' => NULL,
    'created_at' => '2023-05-24 03:28:13',
    'updated_at' => '2023-05-24 03:28:13',
  ),
  24 => 
  array (
    'id' => 26,
    'name' => 'Digital Media Art',
    'slug' => 'Digital-Media-Art',
    'image' => NULL,
    'description' => NULL,
    'created_at' => '2023-05-24 03:28:22',
    'updated_at' => '2023-05-24 03:28:22',
  ),
  25 => 
  array (
    'id' => 27,
    'name' => 'Intelligent Construction',
    'slug' => 'Intelligent-Construction',
    'image' => NULL,
    'description' => NULL,
    'created_at' => '2023-05-24 03:28:40',
    'updated_at' => '2023-05-24 03:28:40',
  ),
  26 => 
  array (
    'id' => 28,
    'name' => 'Finance',
    'slug' => 'Finance',
    'image' => NULL,
    'description' => NULL,
    'created_at' => '2023-05-24 03:28:58',
    'updated_at' => '2023-05-24 03:28:58',
  ),
  27 => 
  array (
    'id' => 29,
    'name' => 'Business English',
    'slug' => 'Business-English',
    'image' => NULL,
    'description' => 'Business English',
    'created_at' => '2025-07-28 01:49:03',
    'updated_at' => '2025-07-28 01:49:03',
  ),
  28 => 
  array (
    'id' => 30,
    'name' => 'English',
    'slug' => 'English',
    'image' => NULL,
    'description' => 'English',
    'created_at' => '2025-07-28 01:49:28',
    'updated_at' => '2025-07-28 01:49:28',
  ),
  29 => 
  array (
    'id' => 31,
    'name' => 'Animal Medical Science',
    'slug' => 'Animal-Medical-Science',
    'image' => NULL,
    'description' => NULL,
    'created_at' => '2025-07-28 04:42:16',
    'updated_at' => '2025-07-28 04:42:16',
  ),
  30 => 
  array (
    'id' => 32,
    'name' => 'Agricultural',
    'slug' => 'Agricultural',
    'image' => NULL,
    'description' => NULL,
    'created_at' => '2025-07-28 22:25:54',
    'updated_at' => '2025-07-28 22:25:54',
  ),
  31 => 
  array (
    'id' => 33,
    'name' => 'Mechanical Engineering',
    'slug' => 'Mechanical-Engineering',
    'image' => NULL,
    'description' => NULL,
    'created_at' => '2025-08-02 23:27:58',
    'updated_at' => '2025-08-02 23:27:58',
  ),
  32 => 
  array (
    'id' => 34,
    'name' => 'Chinese language',
    'slug' => 'Chinese-language',
    'image' => NULL,
    'description' => 'Chinese language  one year',
    'created_at' => '2025-08-03 22:41:49',
    'updated_at' => '2025-08-03 22:41:49',
  ),
  33 => 
  array (
    'id' => 35,
    'name' => 'Clinical Medicine',
    'slug' => 'Clinical-Medicine',
    'image' => NULL,
    'description' => NULL,
    'created_at' => '2025-08-05 02:59:15',
    'updated_at' => '2025-08-05 02:59:15',
  ),
  34 => 
  array (
    'id' => 36,
    'name' => 'Forestry',
    'slug' => 'Forestry',
    'image' => NULL,
    'description' => NULL,
    'created_at' => '2025-08-16 23:17:12',
    'updated_at' => '2025-08-16 23:17:12',
  ),
));
    }
}
