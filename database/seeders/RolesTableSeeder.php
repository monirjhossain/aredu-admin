<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userType = [
            'Super Admin', 
            'Admin',
            'Manager',
            'Branch Manager',
            'Account',
            'IT Officer',
            'Editor',
            'Counselor',
            'Student'
        ];
        
        for($i = 0 ; $i < count($userType); $i++){
            $user = new Role();
            $slug = Str::slug($userType[$i]);
            $user->fill([
                'user_type'=> $userType[$i],
                'slug'=>  $slug,
                'description'=>$userType[$i],
                'status'=>1,
            ])->save();
        }
    }
}
