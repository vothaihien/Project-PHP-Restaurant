<?php

namespace Database\Seeders;

use Database\Seeders\AddressSeeder;
use Database\Seeders\UserTableSeeder;
use Database\Seeders\DriverTableSeeder;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Database\Seeders\CategoryRestaurantTableSeeder;
use Database\Seeders\CategoryTableSeeder;
use Illuminate\Database\Seeder;
use Database\Seeders\MenuTableSeeder;
use Database\Seeders\PigeonTableSeeder;
use Database\Seeders\RestaurantTableSeeder;
use Database\Seeders\ReviewTableSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserTableSeeder::class,
            DriverTableSeeder::class,
            PigeonTableSeeder::class,
            CategoryTableSeeder::class,
            RestaurantTableSeeder::class,
            AddressSeeder::class,
            CategoryRestaurantTableSeeder::class,
            MenuTableSeeder::class,
            RestaurantHoursTableSeeder::class,
            ReviewTableSeeder::class,
        ]);
    }
}