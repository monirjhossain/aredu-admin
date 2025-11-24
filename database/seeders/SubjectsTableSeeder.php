<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubjectsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('subjects')->insert(array (
  0 => 
  array (
    'id' => 1,
    'parent_id' => NULL,
    'name' => 'Computer Science And Technology',
    'status' => 1,
    'created_at' => '2023-08-13 23:07:20',
    'updated_at' => '2023-08-13 23:07:20',
  ),
  1 => 
  array (
    'id' => 2,
    'parent_id' => NULL,
    'name' => 'pharmacy',
    'status' => 1,
    'created_at' => '2023-10-10 21:41:13',
    'updated_at' => '2023-10-10 21:41:13',
  ),
  2 => 
  array (
    'id' => 3,
    'parent_id' => NULL,
    'name' => 'civil engineering',
    'status' => 1,
    'created_at' => '2023-10-10 21:41:57',
    'updated_at' => '2023-10-10 21:41:57',
  ),
  3 => 
  array (
    'id' => 4,
    'parent_id' => NULL,
    'name' => 'Software Engineering',
    'status' => 1,
    'created_at' => '2023-10-10 21:42:36',
    'updated_at' => '2023-10-10 21:42:36',
  ),
  4 => 
  array (
    'id' => 5,
    'parent_id' => NULL,
    'name' => 'international trade and economics',
    'status' => 1,
    'created_at' => '2023-10-10 21:43:23',
    'updated_at' => '2023-10-10 21:43:23',
  ),
  5 => 
  array (
    'id' => 6,
    'parent_id' => NULL,
    'name' => 'applied chemistry',
    'status' => 1,
    'created_at' => '2023-10-10 21:44:19',
    'updated_at' => '2023-10-10 21:44:19',
  ),
  6 => 
  array (
    'id' => 7,
    'parent_id' => NULL,
    'name' => 'Mechanical engineering',
    'status' => 1,
    'created_at' => '2023-10-10 21:44:53',
    'updated_at' => '2023-10-10 21:44:53',
  ),
  7 => 
  array (
    'id' => 8,
    'parent_id' => NULL,
    'name' => 'nursing',
    'status' => 1,
    'created_at' => '2023-10-10 21:45:38',
    'updated_at' => '2023-10-10 21:45:38',
  ),
  8 => 
  array (
    'id' => 9,
    'parent_id' => NULL,
    'name' => 'biology',
    'status' => 1,
    'created_at' => '2023-10-10 21:45:51',
    'updated_at' => '2023-10-10 21:45:51',
  ),
  9 => 
  array (
    'id' => 10,
    'parent_id' => NULL,
    'name' => 'chemistry',
    'status' => 1,
    'created_at' => '2023-10-10 21:46:06',
    'updated_at' => '2023-10-10 21:46:06',
  ),
  10 => 
  array (
    'id' => 11,
    'parent_id' => NULL,
    'name' => 'english',
    'status' => 1,
    'created_at' => '2023-10-10 21:46:22',
    'updated_at' => '2023-10-10 21:46:22',
  ),
  11 => 
  array (
    'id' => 12,
    'parent_id' => NULL,
    'name' => 'textile engineering',
    'status' => 1,
    'created_at' => '2023-10-10 21:46:47',
    'updated_at' => '2023-10-10 21:46:47',
  ),
  12 => 
  array (
    'id' => 13,
    'parent_id' => NULL,
    'name' => 'Electrical Engineering and Automation',
    'status' => 1,
    'created_at' => '2023-10-10 21:50:39',
    'updated_at' => '2023-10-10 21:50:39',
  ),
  13 => 
  array (
    'id' => 14,
    'parent_id' => NULL,
    'name' => 'Finance',
    'status' => 1,
    'created_at' => '2023-10-10 21:50:48',
    'updated_at' => '2023-10-10 21:50:48',
  ),
  14 => 
  array (
    'id' => 15,
    'parent_id' => NULL,
    'name' => 'Artificial Intelligence',
    'status' => 1,
    'created_at' => '2023-10-10 21:50:54',
    'updated_at' => '2023-10-10 21:50:54',
  ),
  15 => 
  array (
    'id' => 16,
    'parent_id' => NULL,
    'name' => 'mbbs',
    'status' => 1,
    'created_at' => '2023-10-10 21:51:20',
    'updated_at' => '2023-10-10 21:51:20',
  ),
  16 => 
  array (
    'id' => 17,
    'parent_id' => NULL,
    'name' => 'Business Administration',
    'status' => 1,
    'created_at' => '2023-10-10 21:53:44',
    'updated_at' => '2023-10-10 21:53:44',
  ),
  17 => 
  array (
    'id' => 18,
    'parent_id' => NULL,
    'name' => 'petroleum engineering',
    'status' => 1,
    'created_at' => '2023-10-10 21:55:56',
    'updated_at' => '2023-10-10 21:55:56',
  ),
  18 => 
  array (
    'id' => 19,
    'parent_id' => NULL,
    'name' => 'mining engineering',
    'status' => 1,
    'created_at' => '2023-10-10 21:57:08',
    'updated_at' => '2023-10-10 21:57:08',
  ),
  19 => 
  array (
    'id' => 20,
    'parent_id' => NULL,
    'name' => 'tourism management',
    'status' => 1,
    'created_at' => '2023-11-12 04:03:43',
    'updated_at' => '2023-11-12 04:03:43',
  ),
  20 => 
  array (
    'id' => 21,
    'parent_id' => NULL,
    'name' => 'e commerce',
    'status' => 1,
    'created_at' => '2023-11-12 04:04:25',
    'updated_at' => '2023-11-12 04:04:25',
  ),
  21 => 
  array (
    'id' => 22,
    'parent_id' => NULL,
    'name' => 'big data technology and application',
    'status' => 1,
    'created_at' => '2023-11-12 04:05:18',
    'updated_at' => '2023-11-12 04:05:18',
  ),
  22 => 
  array (
    'id' => 23,
    'parent_id' => NULL,
    'name' => 'Fashion Design',
    'status' => 1,
    'created_at' => '2023-11-15 05:27:29',
    'updated_at' => '2023-11-15 05:27:29',
  ),
  23 => 
  array (
    'id' => 24,
    'parent_id' => NULL,
    'name' => 'Mechanical Manufacture and Automation',
    'status' => 1,
    'created_at' => '2023-11-15 21:39:13',
    'updated_at' => '2023-11-20 02:31:27',
  ),
  24 => 
  array (
    'id' => 25,
    'parent_id' => NULL,
    'name' => 'Industrial Robotics Technology',
    'status' => 1,
    'created_at' => '2023-11-15 21:39:57',
    'updated_at' => '2023-11-15 21:39:57',
  ),
  25 => 
  array (
    'id' => 26,
    'parent_id' => NULL,
    'name' => 'Unmanned Aerial Vehicle Application Technology',
    'status' => 1,
    'created_at' => '2023-11-15 21:40:52',
    'updated_at' => '2023-11-15 21:40:52',
  ),
  26 => 
  array (
    'id' => 27,
    'parent_id' => NULL,
    'name' => 'Big Data Technology and Application ',
    'status' => 1,
    'created_at' => '2023-11-15 21:43:12',
    'updated_at' => '2023-11-15 21:43:12',
  ),
  27 => 
  array (
    'id' => 28,
    'parent_id' => NULL,
    'name' => 'Computer Network Technology',
    'status' => 1,
    'created_at' => '2023-11-15 21:44:19',
    'updated_at' => '2023-11-15 21:44:19',
  ),
  28 => 
  array (
    'id' => 29,
    'parent_id' => NULL,
    'name' => 'New Energy Vehicle Technology',
    'status' => 1,
    'created_at' => '2023-11-15 21:48:36',
    'updated_at' => '2023-11-15 21:48:36',
  ),
  29 => 
  array (
    'id' => 30,
    'parent_id' => NULL,
    'name' => 'Architecture Intelligence Engineering Technology',
    'status' => 1,
    'created_at' => '2023-11-15 21:50:10',
    'updated_at' => '2023-11-15 21:50:10',
  ),
  30 => 
  array (
    'id' => 31,
    'parent_id' => NULL,
    'name' => 'Logistics (Intelligent logistics)',
    'status' => 1,
    'created_at' => '2023-11-15 21:58:19',
    'updated_at' => '2023-11-15 21:58:19',
  ),
  31 => 
  array (
    'id' => 32,
    'parent_id' => NULL,
    'name' => 'International Business',
    'status' => 1,
    'created_at' => '2023-11-15 21:58:35',
    'updated_at' => '2023-11-15 21:58:35',
  ),
  32 => 
  array (
    'id' => 33,
    'parent_id' => NULL,
    'name' => 'Bio-technology',
    'status' => 1,
    'created_at' => '2023-11-15 21:59:44',
    'updated_at' => '2023-11-20 04:28:47',
  ),
  33 => 
  array (
    'id' => 34,
    'parent_id' => NULL,
    'name' => 'Aerospace Engineering',
    'status' => 1,
    'created_at' => '2023-11-15 21:59:58',
    'updated_at' => '2023-11-15 21:59:58',
  ),
  34 => 
  array (
    'id' => 35,
    'parent_id' => NULL,
    'name' => 'Naval Architecture & Marine Engineering',
    'status' => 1,
    'created_at' => '2023-11-15 22:00:18',
    'updated_at' => '2023-11-15 22:00:18',
  ),
  35 => 
  array (
    'id' => 36,
    'parent_id' => NULL,
    'name' => 'Materials Science & Engineering',
    'status' => 1,
    'created_at' => '2023-11-15 22:00:33',
    'updated_at' => '2023-11-15 22:00:33',
  ),
  36 => 
  array (
    'id' => 37,
    'parent_id' => NULL,
    'name' => 'Microelectronic Science & Engineering',
    'status' => 1,
    'created_at' => '2023-11-15 22:01:07',
    'updated_at' => '2023-11-15 22:01:07',
  ),
  37 => 
  array (
    'id' => 38,
    'parent_id' => NULL,
    'name' => 'Internet Finance',
    'status' => 1,
    'created_at' => '2023-11-15 22:03:20',
    'updated_at' => '2023-11-15 22:03:20',
  ),
  38 => 
  array (
    'id' => 39,
    'parent_id' => NULL,
    'name' => 'Electronic Commerce',
    'status' => 1,
    'created_at' => '2023-11-15 22:03:39',
    'updated_at' => '2023-11-15 22:03:39',
  ),
  39 => 
  array (
    'id' => 40,
    'parent_id' => NULL,
    'name' => 'Electronic Commerce',
    'status' => 1,
    'created_at' => '2023-11-15 22:05:07',
    'updated_at' => '2023-11-15 22:05:07',
  ),
  40 => 
  array (
    'id' => 41,
    'parent_id' => NULL,
    'name' => 'Petroleum Chemical Technology',
    'status' => 1,
    'created_at' => '2023-11-15 22:05:39',
    'updated_at' => '2023-11-15 22:05:39',
  ),
  41 => 
  array (
    'id' => 42,
    'parent_id' => NULL,
    'name' => 'Petroleum Chemical Technology',
    'status' => 1,
    'created_at' => '2023-11-15 22:05:57',
    'updated_at' => '2023-11-15 22:05:57',
  ),
  42 => 
  array (
    'id' => 43,
    'parent_id' => NULL,
    'name' => 'Industrial Analysis Technology',
    'status' => 1,
    'created_at' => '2023-11-15 22:06:28',
    'updated_at' => '2023-11-15 22:06:28',
  ),
  43 => 
  array (
    'id' => 44,
    'parent_id' => NULL,
    'name' => 'Internet of Things Application Technology',
    'status' => 1,
    'created_at' => '2023-11-15 22:06:51',
    'updated_at' => '2023-11-15 22:06:51',
  ),
  44 => 
  array (
    'id' => 45,
    'parent_id' => NULL,
    'name' => 'Art Design',
    'status' => 1,
    'created_at' => '2023-11-15 22:07:08',
    'updated_at' => '2023-11-15 22:07:08',
  ),
  45 => 
  array (
    'id' => 46,
    'parent_id' => NULL,
    'name' => 'Rehabilitation Traditional Chinese Medicine',
    'status' => 1,
    'created_at' => '2023-11-15 22:07:58',
    'updated_at' => '2023-11-15 22:07:58',
  ),
  46 => 
  array (
    'id' => 47,
    'parent_id' => NULL,
    'name' => 'Hotel Management',
    'status' => 1,
    'created_at' => '2023-11-15 22:08:41',
    'updated_at' => '2023-11-15 22:08:41',
  ),
  47 => 
  array (
    'id' => 48,
    'parent_id' => NULL,
    'name' => 'Modern Communication Technology',
    'status' => 1,
    'created_at' => '2023-11-15 22:09:31',
    'updated_at' => '2023-11-15 22:09:31',
  ),
  48 => 
  array (
    'id' => 49,
    'parent_id' => NULL,
    'name' => 'Numerical Control',
    'status' => 1,
    'created_at' => '2023-11-15 22:10:09',
    'updated_at' => '2023-11-15 22:10:09',
  ),
  49 => 
  array (
    'id' => 50,
    'parent_id' => NULL,
    'name' => 'IOT Application Technology',
    'status' => 1,
    'created_at' => '2023-11-15 22:10:33',
    'updated_at' => '2023-11-15 22:10:33',
  ),
  50 => 
  array (
    'id' => 51,
    'parent_id' => NULL,
    'name' => 'Communication Engineering',
    'status' => 1,
    'created_at' => '2023-11-15 22:11:09',
    'updated_at' => '2023-11-15 22:11:09',
  ),
  51 => 
  array (
    'id' => 52,
    'parent_id' => NULL,
    'name' => 'Aeronautical Engineering',
    'status' => 1,
    'created_at' => '2023-11-15 22:12:04',
    'updated_at' => '2023-11-15 22:12:04',
  ),
  52 => 
  array (
    'id' => 53,
    'parent_id' => NULL,
    'name' => 'Aircraft Maintenance Engineering',
    'status' => 1,
    'created_at' => '2023-11-15 22:12:28',
    'updated_at' => '2023-11-15 22:12:28',
  ),
  53 => 
  array (
    'id' => 54,
    'parent_id' => NULL,
    'name' => 'Chemical Engineering & Technology',
    'status' => 1,
    'created_at' => '2023-11-15 22:17:26',
    'updated_at' => '2023-11-15 22:17:26',
  ),
  54 => 
  array (
    'id' => 55,
    'parent_id' => NULL,
    'name' => 'LAW',
    'status' => 1,
    'created_at' => '2023-11-16 02:47:34',
    'updated_at' => '2023-11-16 02:47:34',
  ),
  55 => 
  array (
    'id' => 56,
    'parent_id' => NULL,
    'name' => 'Mathematics and Applied Mathematics',
    'status' => 1,
    'created_at' => '2023-11-16 02:52:31',
    'updated_at' => '2023-11-16 02:52:31',
  ),
  56 => 
  array (
    'id' => 57,
    'parent_id' => NULL,
    'name' => 'HOTEL MANAGEMENT',
    'status' => 1,
    'created_at' => '2023-11-16 03:13:56',
    'updated_at' => '2023-11-16 03:13:56',
  ),
  57 => 
  array (
    'id' => 58,
    'parent_id' => NULL,
    'name' => 'ELECTRONIC and INFORMATION ENGINNERING',
    'status' => 1,
    'created_at' => '2023-11-16 03:24:15',
    'updated_at' => '2023-11-20 04:10:57',
  ),
  58 => 
  array (
    'id' => 59,
    'parent_id' => NULL,
    'name' => 'Accounting',
    'status' => 1,
    'created_at' => '2023-11-16 03:48:45',
    'updated_at' => '2023-11-16 03:48:45',
  ),
  59 => 
  array (
    'id' => 60,
    'parent_id' => NULL,
    'name' => 'Light Chemical Engineering',
    'status' => 1,
    'created_at' => '2023-11-20 02:22:11',
    'updated_at' => '2023-11-20 02:22:11',
  ),
  60 => 
  array (
    'id' => 61,
    'parent_id' => NULL,
    'name' => 'Pre-school Education',
    'status' => 1,
    'created_at' => '2023-11-20 02:23:14',
    'updated_at' => '2023-11-20 02:23:14',
  ),
  61 => 
  array (
    'id' => 62,
    'parent_id' => NULL,
    'name' => 'Polymer Materials and Engineering',
    'status' => 1,
    'created_at' => '2023-11-20 02:23:39',
    'updated_at' => '2023-11-20 02:23:39',
  ),
  62 => 
  array (
    'id' => 63,
    'parent_id' => NULL,
    'name' => 'Electrical and Electronic Engineering',
    'status' => 1,
    'created_at' => '2023-11-20 02:36:12',
    'updated_at' => '2023-11-20 02:36:12',
  ),
  63 => 
  array (
    'id' => 64,
    'parent_id' => NULL,
    'name' => 'Electrical Engineering',
    'status' => 1,
    'created_at' => '2023-11-20 02:40:49',
    'updated_at' => '2023-11-20 02:40:49',
  ),
  64 => 
  array (
    'id' => 65,
    'parent_id' => NULL,
    'name' => 'Architecture',
    'status' => 1,
    'created_at' => '2023-11-20 02:41:12',
    'updated_at' => '2023-11-20 02:41:12',
  ),
  65 => 
  array (
    'id' => 66,
    'parent_id' => NULL,
    'name' => 'Mechatronic Engineering',
    'status' => 1,
    'created_at' => '2023-11-20 03:33:06',
    'updated_at' => '2023-11-20 03:33:06',
  ),
  66 => 
  array (
    'id' => 67,
    'parent_id' => NULL,
    'name' => 'Mechanical Design & Manufacturing & their Automation',
    'status' => 1,
    'created_at' => '2023-11-20 03:33:26',
    'updated_at' => '2023-11-20 03:33:26',
  ),
  67 => 
  array (
    'id' => 68,
    'parent_id' => NULL,
    'name' => 'Energy & Power Engineering',
    'status' => 1,
    'created_at' => '2023-11-20 03:34:11',
    'updated_at' => '2023-11-20 03:34:11',
  ),
  68 => 
  array (
    'id' => 69,
    'parent_id' => NULL,
    'name' => 'Materials Formation & Control Engineering',
    'status' => 1,
    'created_at' => '2023-11-20 03:35:32',
    'updated_at' => '2023-11-20 03:35:32',
  ),
  69 => 
  array (
    'id' => 70,
    'parent_id' => NULL,
    'name' => 'Public Administration',
    'status' => 1,
    'created_at' => '2023-11-20 04:03:03',
    'updated_at' => '2023-11-20 04:03:03',
  ),
  70 => 
  array (
    'id' => 71,
    'parent_id' => NULL,
    'name' => 'Mineral Processing Engineering',
    'status' => 1,
    'created_at' => '2023-11-20 04:03:26',
    'updated_at' => '2023-11-20 04:03:26',
  ),
  71 => 
  array (
    'id' => 72,
    'parent_id' => NULL,
    'name' => 'Safety Engineering',
    'status' => 1,
    'created_at' => '2023-11-20 04:03:46',
    'updated_at' => '2023-11-20 04:03:46',
  ),
  72 => 
  array (
    'id' => 73,
    'parent_id' => NULL,
    'name' => 'Transportation Engineering',
    'status' => 1,
    'created_at' => '2023-11-20 04:16:10',
    'updated_at' => '2023-11-20 04:16:10',
  ),
  73 => 
  array (
    'id' => 74,
    'parent_id' => NULL,
    'name' => 'Surveying & Mapping Engineering',
    'status' => 1,
    'created_at' => '2023-11-20 04:18:07',
    'updated_at' => '2023-11-20 04:18:07',
  ),
  74 => 
  array (
    'id' => 75,
    'parent_id' => NULL,
    'name' => 'Pharmaceutical Science',
    'status' => 1,
    'created_at' => '2023-11-20 04:18:30',
    'updated_at' => '2023-11-20 04:18:30',
  ),
  75 => 
  array (
    'id' => 76,
    'parent_id' => NULL,
    'name' => 'Automation',
    'status' => 1,
    'created_at' => '2023-11-20 04:18:53',
    'updated_at' => '2023-11-20 04:18:53',
  ),
  76 => 
  array (
    'id' => 77,
    'parent_id' => NULL,
    'name' => 'Macromolecular Materials & Engineering',
    'status' => 1,
    'created_at' => '2023-11-20 04:27:03',
    'updated_at' => '2023-11-20 04:27:03',
  ),
  77 => 
  array (
    'id' => 78,
    'parent_id' => NULL,
    'name' => 'Environment Engineering',
    'status' => 1,
    'created_at' => '2023-11-20 04:27:52',
    'updated_at' => '2023-11-20 04:27:52',
  ),
  78 => 
  array (
    'id' => 79,
    'parent_id' => NULL,
    'name' => 'Animal Science',
    'status' => 1,
    'created_at' => '2023-11-20 04:30:10',
    'updated_at' => '2023-11-20 04:30:10',
  ),
  79 => 
  array (
    'id' => 80,
    'parent_id' => NULL,
    'name' => 'Environmental Engineering',
    'status' => 1,
    'created_at' => '2023-11-20 04:47:49',
    'updated_at' => '2023-11-20 04:47:49',
  ),
  80 => 
  array (
    'id' => 81,
    'parent_id' => NULL,
    'name' => 'Water Supply & Drainage',
    'status' => 1,
    'created_at' => '2023-11-20 04:47:56',
    'updated_at' => '2023-11-20 04:47:56',
  ),
  81 => 
  array (
    'id' => 82,
    'parent_id' => NULL,
    'name' => 'Vehicle Engineering',
    'status' => 1,
    'created_at' => '2023-12-03 02:08:07',
    'updated_at' => '2023-12-03 02:08:07',
  ),
  82 => 
  array (
    'id' => 83,
    'parent_id' => NULL,
    'name' => 'Logistics Management',
    'status' => 1,
    'created_at' => '2023-12-03 02:08:51',
    'updated_at' => '2023-12-03 02:08:51',
  ),
  83 => 
  array (
    'id' => 84,
    'parent_id' => NULL,
    'name' => 'Urban Railway Transportation',
    'status' => 1,
    'created_at' => '2023-12-03 02:11:04',
    'updated_at' => '2023-12-03 02:11:04',
  ),
  84 => 
  array (
    'id' => 85,
    'parent_id' => NULL,
    'name' => 'Railway Transportation',
    'status' => 1,
    'created_at' => '2023-12-03 02:11:56',
    'updated_at' => '2023-12-03 02:11:56',
  ),
  85 => 
  array (
    'id' => 86,
    'parent_id' => NULL,
    'name' => 'Marketing',
    'status' => 1,
    'created_at' => '2023-12-03 02:46:38',
    'updated_at' => '2023-12-03 02:46:38',
  ),
  86 => 
  array (
    'id' => 87,
    'parent_id' => NULL,
    'name' => 'Applied Statistics',
    'status' => 1,
    'created_at' => '2023-12-03 03:02:05',
    'updated_at' => '2023-12-03 03:02:05',
  ),
  87 => 
  array (
    'id' => 88,
    'parent_id' => NULL,
    'name' => 'Photoelectric Information & Engineering',
    'status' => 1,
    'created_at' => '2023-12-03 03:02:52',
    'updated_at' => '2023-12-03 03:02:52',
  ),
  88 => 
  array (
    'id' => 89,
    'parent_id' => NULL,
    'name' => 'Traffic Engineering',
    'status' => 1,
    'created_at' => '2023-12-03 03:03:30',
    'updated_at' => '2023-12-03 03:03:30',
  ),
  89 => 
  array (
    'id' => 90,
    'parent_id' => NULL,
    'name' => 'Intelligent Manufacturing',
    'status' => 1,
    'created_at' => '2023-12-03 03:04:01',
    'updated_at' => '2023-12-03 03:04:01',
  ),
  90 => 
  array (
    'id' => 91,
    'parent_id' => NULL,
    'name' => 'Automation',
    'status' => 1,
    'created_at' => '2023-12-03 03:04:33',
    'updated_at' => '2023-12-03 03:04:33',
  ),
  91 => 
  array (
    'id' => 92,
    'parent_id' => NULL,
    'name' => 'supply chain management',
    'status' => 1,
    'created_at' => '2023-12-17 22:19:41',
    'updated_at' => '2023-12-17 22:19:41',
  ),
  92 => 
  array (
    'id' => 93,
    'parent_id' => NULL,
    'name' => 'Architecture Department',
    'status' => 1,
    'created_at' => '2023-12-31 00:39:34',
    'updated_at' => '2023-12-31 00:39:34',
  ),
  93 => 
  array (
    'id' => 94,
    'parent_id' => NULL,
    'name' => 'Chinese Language Course',
    'status' => 1,
    'created_at' => '2024-11-08 02:58:44',
    'updated_at' => '2024-11-08 02:58:44',
  ),
  94 => 
  array (
    'id' => 95,
    'parent_id' => NULL,
    'name' => 'Hospitality Management',
    'status' => 1,
    'created_at' => '2025-07-28 00:47:53',
    'updated_at' => '2025-07-28 00:47:53',
  ),
  95 => 
  array (
    'id' => 96,
    'parent_id' => NULL,
    'name' => 'Business English',
    'status' => 1,
    'created_at' => '2025-07-28 01:14:19',
    'updated_at' => '2025-07-28 01:14:19',
  ),
  96 => 
  array (
    'id' => 97,
    'parent_id' => NULL,
    'name' => 'Physics',
    'status' => 1,
    'created_at' => '2025-07-28 02:09:03',
    'updated_at' => '2025-07-28 02:09:03',
  ),
  97 => 
  array (
    'id' => 99,
    'parent_id' => NULL,
    'name' => 'Materials Science and Engineering',
    'status' => 1,
    'created_at' => '2025-07-28 02:09:47',
    'updated_at' => '2025-07-28 02:09:47',
  ),
  98 => 
  array (
    'id' => 101,
    'parent_id' => NULL,
    'name' => 'Animal Medical Science',
    'status' => 1,
    'created_at' => '2025-07-28 03:57:45',
    'updated_at' => '2025-07-28 03:57:45',
  ),
  99 => 
  array (
    'id' => 102,
    'parent_id' => NULL,
    'name' => 'Chemical Biology',
    'status' => 1,
    'created_at' => '2025-07-28 03:57:58',
    'updated_at' => '2025-07-28 03:57:58',
  ),
  100 => 
  array (
    'id' => 103,
    'parent_id' => NULL,
    'name' => 'Biotechnology',
    'status' => 1,
    'created_at' => '2025-07-28 03:58:11',
    'updated_at' => '2025-07-28 03:58:11',
  ),
  101 => 
  array (
    'id' => 104,
    'parent_id' => NULL,
    'name' => 'Agricultural',
    'status' => 1,
    'created_at' => '2025-07-28 22:21:51',
    'updated_at' => '2025-07-28 22:21:51',
  ),
  102 => 
  array (
    'id' => 105,
    'parent_id' => NULL,
    'name' => 'Public Relations',
    'status' => 1,
    'created_at' => '2025-07-31 22:29:56',
    'updated_at' => '2025-07-31 22:29:56',
  ),
  103 => 
  array (
    'id' => 106,
    'parent_id' => NULL,
    'name' => 'Economy Business',
    'status' => 1,
    'created_at' => '2025-07-31 22:56:26',
    'updated_at' => '2025-07-31 22:56:26',
  ),
  104 => 
  array (
    'id' => 107,
    'parent_id' => NULL,
    'name' => 'Clinical Medicine',
    'status' => 1,
    'created_at' => '2025-08-05 02:54:55',
    'updated_at' => '2025-08-05 02:54:55',
  ),
  105 => 
  array (
    'id' => 108,
    'parent_id' => NULL,
    'name' => 'Clinical Medicine',
    'status' => 1,
    'created_at' => '2025-08-05 02:58:46',
    'updated_at' => '2025-08-05 02:58:46',
  ),
  106 => 
  array (
    'id' => 109,
    'parent_id' => NULL,
    'name' => 'Wood Science and Engineering',
    'status' => 1,
    'created_at' => '2025-08-16 23:14:58',
    'updated_at' => '2025-08-16 23:14:58',
  ),
  107 => 
  array (
    'id' => 110,
    'parent_id' => NULL,
    'name' => 'Forestry',
    'status' => 1,
    'created_at' => '2025-08-16 23:15:45',
    'updated_at' => '2025-08-16 23:15:45',
  ),
  108 => 
  array (
    'id' => 111,
    'parent_id' => NULL,
    'name' => 'Mechatronics Technology',
    'status' => 1,
    'created_at' => '2025-08-17 01:49:44',
    'updated_at' => '2025-08-17 01:50:32',
  ),
  109 => 
  array (
    'id' => 112,
    'parent_id' => NULL,
    'name' => 'Attire and Costume Design',
    'status' => 1,
    'created_at' => '2025-08-17 01:50:03',
    'updated_at' => '2025-08-17 01:50:03',
  ),
  110 => 
  array (
    'id' => 113,
    'parent_id' => NULL,
    'name' => 'Textile Inspection and Trade',
    'status' => 1,
    'created_at' => '2025-08-17 01:50:18',
    'updated_at' => '2025-08-17 01:50:18',
  ),
  111 => 
  array (
    'id' => 114,
    'parent_id' => NULL,
    'name' => 'Robot Engineering',
    'status' => 1,
    'created_at' => '2025-08-17 02:52:33',
    'updated_at' => '2025-08-17 02:52:33',
  ),
));
    }
}
