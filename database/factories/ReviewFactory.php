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
        // Các comment mẫu tiếng Việt
        $comments = [
            'Nhà hàng rất tốt, đồ ăn ngon và phục vụ nhiệt tình.',
            'Món ăn đậm đà, giá cả hợp lý, sẽ quay lại lần sau.',
            'Không gian đẹp, thức ăn tươi ngon, nhân viên thân thiện.',
            'Đồ ăn được chế biến cẩn thận, hương vị tuyệt vời.',
            'Nhà hàng sạch sẽ, món ăn đa dạng, rất đáng để thử.',
            'Chất lượng tốt, giá cả phải chăng, giao hàng nhanh.',
            'Món ăn ngon miệng, phục vụ chuyên nghiệp, rất hài lòng.',
            'Thức ăn tươi ngon, không gian thoải mái, sẽ giới thiệu cho bạn bè.',
            'Nhà hàng có nhiều món đặc sắc, hương vị đậm đà.',
            'Dịch vụ tốt, đồ ăn chất lượng, giá cả hợp lý.',
            'Món ăn được trình bày đẹp mắt, hương vị tuyệt vời.',
            'Nhà hàng có không gian rộng rãi, phù hợp cho gia đình.',
            'Đồ ăn nóng hổi, thơm ngon, phục vụ nhanh chóng.',
            'Chất lượng món ăn tốt, giá cả phải chăng.',
            'Nhà hàng có nhiều lựa chọn, món nào cũng ngon.',
        ];
        
        return [
            'user_id' => $this->faker->numberBetween(1, 30),
            'restaurant_id' => $this->faker->numberBetween(1, 40),
            'rating' => $this->faker->numberBetween(2, 5),
            'comment' => $this->faker->randomElement($comments),
            'created_at' => now(),
        ];
    }
}