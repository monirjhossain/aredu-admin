<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
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
            $user = new User();

            $slug = Str::slug($userType[$i]);

            $parts = explode(' ', $userType[$i]);
            $firstName = $parts[0];
            $lastName = trim(substr($userType[$i], strlen($firstName)));

            $user->fill([
                'name'=> $userType[$i],
                'username'=>$slug,
                'email'=> $slug.'@areducation.net',
                'password'=> 123123,
                'branch'=> '1',
                'related_to'=> $i == 0 ? null : ($i == 1 ? 1 : ($i == 2 ? 2 : ($i == 3 ? 3 : ($i == 4 ? 3 : ($i == 5 ? 3 : ($i == 6 ? 3 : 3)))))),
                'role_id' => $i+1
            ])->save();
        }
    }
}
