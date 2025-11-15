<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::create(['name' => '$']);
        Category::create(['name' => '$$']);
        Category::create(['name' => '$$$']);
        Category::create(['name' => 'Bữa sáng']);
        Category::create(['name' => 'Tráng miệng']);
        Category::create(['name' => 'Súp']);
        Category::create(['name' => 'Ý']);
        Category::create(['name' => 'Hy Lạp']);
        Category::create(['name' => 'Gà']);
        Category::create(['name' => 'Salad']);
        Category::create(['name' => 'Khoai tây chiên']);
        Category::create(['name' => 'Châu Á']);
        Category::create(['name' => 'Thuần chay']);
        Category::create(['name' => 'Pizza']);
        Category::create(['name' => 'Sushi']);
        Category::create(['name' => 'Không gluten']);
        Category::create(['name' => 'Mì ống']);
        Category::create(['name' => 'Cay']);
        Category::create(['name' => 'Bánh mì sandwich']);
        Category::create(['name' => 'Burger']);
        Category::create(['name' => 'Nướng']);
        Category::create(['name' => 'Shawarma']);
        Category::create(['name' => 'Canada']);
        Category::create(['name' => 'Mỹ']);
        Category::create(['name' => 'Trung Quốc']);
        Category::create(['name' => 'Mexico']);
        Category::create(['name' => 'Ấn Độ']);
        Category::create(['name' => 'Caribbean']);
        Category::create(['name' => 'Đồ ăn vặt']);
        Category::create(['name' => 'Đồ uống']);
        Category::create(['name' => 'Cơm']);
        Category::create(['name' => 'Mì']);
        Category::create(['name' => 'Đồ ăn nhanh']);
    }
}