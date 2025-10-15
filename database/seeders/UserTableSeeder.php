<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Ace Ventura',
            'email' => 'ace@gmail.com',
            'phone' => '5146549879',
            'email_verified_at' => now(),
            'password' => '$2y$10$OWDVTJjX4OwVRxRtf4SX1uJsE.e4FXITPKAp2PSwKtj5QeceaPIk.' // aceaceace
        ]);

        User::factory()->count(29)->create();
    }
}