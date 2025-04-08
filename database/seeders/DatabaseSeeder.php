<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call the method to create the firebasejson.json file
        // $this->createFirebaseJson();


        $User = \App\Models\User::create([

            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test@godash.com',
            'password' => bcrypt('password'),
            'phone_number' => '+1 (938) 671-2796',
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);




    }

}
