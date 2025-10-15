<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

namespace Database\Factories;

use App\Models\Review;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factories\Factory;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

// $factory->define(Review::class, function (Faker $faker) {
//     return [
//         'user_id' => $faker->numberBetween($min = 1, $max = 30),
//         'restaurant_id' => $faker->numberBetween($min = 1, $max = 40),
//         'rating' => $faker->numberBetween($min = 2, $max = 5),
//         'comment' => $faker->sentence($nbWords = 6, $variableNbWords = true)
//     ];
// });

class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition(): array
    {
        return [
            'user_id' => $this->faker->numberBetween(1, 30),
            'restaurant_id' => $this->faker->numberBetween(1, 40),
            'rating' => $this->faker->numberBetween(2, 5),
            'comment' => $this->faker->sentence(6, true),
            'created_at' => now(),
        ];
    }
}