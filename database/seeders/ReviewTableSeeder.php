<?php

namespace Database\Seeders;

use App\Models\Review;
use App\Models\Restaurant;
use Illuminate\Database\Seeder;

class ReviewTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (Restaurant::all() as $restaurant) {
            Review::factory()->count(10)->create([
                'restaurant_id' => $restaurant->id,
            ]);
        }
    }
}