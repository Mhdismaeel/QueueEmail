<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Let's clear the users table first
        //User::truncate();

       // DB::tabel('role_users')->truncate();

        $adminRole=Role::where('name','admin')->first();
        $authorRole=Role::where('name','author')->first();
        $userRole=Role::where('name','user')->first();

        $admin=User::create([
            'name'=>'Admin',
            'email'=>'admin@localhost.com',
            'password'=>Hash::make('Admin123#')

        ]);


        $author=User::create([
            'name'=>'Author',
            'email'=>'author@localhost.com',
            'password'=>Hash::make('Author123#')

            ]);


         $user=User::create([
            'name'=>'User',
            'email'=>'user@localhost.com',
            'password'=>Hash::make('User123#')

                ]);
    $admin->roles()->attach($adminRole);
    $author->roles()->attach($authorRole);
    $user->roles()->attach($userRole);







    }
}

