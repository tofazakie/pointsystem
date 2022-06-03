<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->delete();
        $users = [[
            'name' => 'Tom Cruise',
            'email' => 'user1@mail.com',
            'password' => Hash::make('secret1')
        ], [
            'name' => 'Daniel Craig',
            'email' => 'user2@mail.com',
            'password' => Hash::make('secret2')
        ], [
            'name' => 'Jason Statham',
            'email' => 'user3@mail.com',
            'password' => Hash::make('secret3')
        ]];

        foreach($users as $user){
            User::create($user);
        }
    }
}
