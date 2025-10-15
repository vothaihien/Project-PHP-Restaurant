<?php

namespace Database\Seeders;

use App\Models\Pigeon;
use Illuminate\Database\Seeder;

class PigeonTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Pigeon::create([
            'name' => 'George',
            'username' => 'George',
            'password' => '$2y$10$KQOT3M/8erDaQ/XRkx45yuB3ttC7eKrpG3e1xk504G0Rjrgp4Jjd.', //GeorgeGeorge
            'is_super' => 1
        ]);
        Pigeon::create([
            'name' => 'Kian',
            'username' => 'Kian',
            'password' => '$2y$10$DngkFilfKN30W6N9.ds/q.7Oe.8V7WREcKDYc0JXKF1R5ZOf34FQ2', //KianKian
            'is_super' => 1
        ]);
        Pigeon::create([
            'name' => 'Terence',
            'username' => 'Terence',
            'password' => '$2y$10$bFwDjHX8aVPevYd52XHUjeWyLfrD71adnDPghXRJZVq3ia5z1UOx.', //TerenceTerence
            'is_super' => 1
        ]);
    }
}