<?php

namespace Database\Seeders;

use App\Models\Restaurant;
use Illuminate\Database\Seeder;

class RestaurantTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Restaurant::create([
            'name' => 'Pizza Pizza',
            'slug' => 'pizza-pizza',
            'email' => 'mark@pizzapizza.com',
            'phone' => '5146549879',
            'password' => '$2y$12$kHZBnAAppOfXP4GrHegquOw12ooCcT8bDOi0V1kkM8X/GYLNnW7uC', // secret
            'category_id' => 14,
            'image' => 'uploads/pizza-pizza.jpg',
            'delivery_fee' => '2.00',
            'active' => 1
        ]);
        Restaurant::create([
            'name' => 'Pizza Hut',
            'slug' => 'pizza-hut',
            'email' => 'hut@pizzahut.com',
            'phone' => '5149889879',
            'password' => '$2y$12$kHZBnAAppOfXP4GrHegquOw12ooCcT8bDOi0V1kkM8X/GYLNnW7uC', // secret
            'category_id' => 14,
            'image' => 'uploads/pizza-hut.jpg',
            'delivery_fee' => '2.50',
            'active' => 1
        ]);
        Restaurant::create([
            'name' => 'KFC',
            'slug' => 'kfc',
            'email' => 'kfc@gmail.com',
            'phone' => '5146549877',
            'password' => '$2y$12$kHZBnAAppOfXP4GrHegquOw12ooCcT8bDOi0V1kkM8X/GYLNnW7uC', // secret
            'category_id' => 9,
            'image' => 'uploads/kfc.jpg',
            'delivery_fee' => '3.00',
            'active' => 1
        ]);
        Restaurant::create([
            'name' => 'Ottavio',
            'slug' => 'ottavio',
            'email' => 'management@ottavio.com',
            'phone' => '4506549787',
            'password' => '$2y$12$kHZBnAAppOfXP4GrHegquOw12ooCcT8bDOi0V1kkM8X/GYLNnW7uC', // secret
            'category_id' => 7,
            'image' => 'uploads/NH1.jpg',
            'active' => 1
        ]);
        Restaurant::create([
            'name' => 'Subway',
            'slug' => 'subway',
            'email' => 'montreal@subway.com',
            'phone' => '5146449844',
            'password' => '$2y$12$kHZBnAAppOfXP4GrHegquOw12ooCcT8bDOi0V1kkM8X/GYLNnW7uC', // secret
            'category_id' => 19,
            'image' => 'uploads/NH2.jpg',
            'active' => 1
        ]);
        Restaurant::create([
            'name' => 'Thaï Express',
            'slug' => 'thai-express',
            'email' => 'owner@thaiexpress.com',
            'phone' => '5145437766',
            'password' => 'y$kHZBnAAppOfXP4GrHegquOw12ooCcT8bDOi0V1kkM8X/GYLNnW7uC', // secret,
            'category_id' => 12,
            'image' => 'uploads/NH3.jpg',
            'active' => 1
        ]);
        Restaurant::create([
            'name' => 'Burger King',
            'slug' => 'burger-king',
            'email' => 'king@burgerking.com',
            'phone' => '5141123217',
            'password' => 'y$kHZBnAAppOfXP4GrHegquOw12ooCcT8bDOi0V1kkM8X/GYLNnW7uC', // secret,
            'category_id' => 20,
            'image' => 'uploads/NH4.jpg',
            'delivery_fee' => '3.50',
            'active' => 1
        ]);
        Restaurant::create([
            'name' => 'In-N-Out Burger',
            'slug' => 'in-n-out-burger',
            'email' => 'restaurants@in-n-out.com',
            'phone' => '4506339527',
            'password' => 'y$kHZBnAAppOfXP4GrHegquOw12ooCcT8bDOi0V1kkM8X/GYLNnW7uC', // secret,
            'category_id' => 20,
            'image' => 'uploads/NH5.jpg',
            'delivery_fee' => '5.00',
            'active' => 1
        ]);
        Restaurant::create([
            'name' => 'Fisshu',
            'slug' => 'fisshu',
            'email' => 'sushi@fisshu.com',
            'phone' => '5143191862',
            'password' => 'y$kHZBnAAppOfXP4GrHegquOw12ooCcT8bDOi0V1kkM8X/GYLNnW7uC', // secret,
            'category_id' => 15,
            'image' => 'uploads/NH6.jpg',
            'active' => 1
        ]);
        Restaurant::create([
            'name' => "Dunn's",
            'slug' => 'dunns',
            'email' => 'info@dunnsfamous.com',
            'phone' => '5143951927',
            'password' => 'y$kHZBnAAppOfXP4GrHegquOw12ooCcT8bDOi0V1kkM8X/GYLNnW7uC', // secret,
            'category_id' => 19,
            'image' => 'uploads/NH7.jpg',
            'delivery_fee' => '5.25',
            'active' => 1
        ]);
        Restaurant::create([
            'name' => 'Scores',
            'slug' => 'scores',
            'email' => 'info@scores.ca',
            'phone' => '5143342828',
            'password' => 'y$kHZBnAAppOfXP4GrHegquOw12ooCcT8bDOi0V1kkM8X/GYLNnW7uC', // secret,
            'category_id' => 9,
            'image' => 'uploads/NH8.jpg',
            'delivery_fee' => '1.00',
            'active' => 1
        ]);
        Restaurant::create([
            'name' => 'Mikes',
            'slug' => 'mikes',
            'email' => 'toujours@mikes.ca',
            'phone' => '5143319655',
            'password' => 'y$kHZBnAAppOfXP4GrHegquOw12ooCcT8bDOi0V1kkM8X/GYLNnW7uC', // secret,
            'category_id' => 14,
            'image' => 'uploads/NH9.jpg',
            'active' => 1
        ]);
        Restaurant::create([
            'name' => 'St-Hubert',
            'slug' => 'st-hubert',
            'email' => 'restaurant@sthubert.ca',
            'phone' => '5146374417',
            'password' => 'y$kHZBnAAppOfXP4GrHegquOw12ooCcT8bDOi0V1kkM8X/GYLNnW7uC', // secret,
            'category_id' => 9,
            'image' => 'uploads/NH10.jpg',
            'delivery_fee' => '1.00',
            'active' => 1
        ]);
        Restaurant::create([
            'name' => 'Boustan',
            'slug' => 'boustan',
            'email' => 'boustancuisine@gmail.com',
            'phone' => '5148433194',
            'password' => 'y$kHZBnAAppOfXP4GrHegquOw12ooCcT8bDOi0V1kkM8X/GYLNnW7uC', // secret,
            'category_id' => 22,
            'image' => 'uploads/NH11.jpg',
            'active' => 1
        ]);
        Restaurant::create([
            'name' => "Wendy's",
            'slug' => 'wendys',
            'email' => 'wendys@info.com',
            'phone' => '5144814060',
            'password' => 'y$kHZBnAAppOfXP4GrHegquOw12ooCcT8bDOi0V1kkM8X/GYLNnW7uC', // secret,
            'category_id' => 20,
            'image' => 'uploads/NH12.jpg',
            'delivery_fee' => '2.25',
            'active' => 1
        ]);
        Restaurant::create([
            'name' => 'BBQ Tandoori',
            'slug' => 'bbq-tandoori',
            'email' => 'bbq@tandoori.ca',
            'phone' => '5145959000',
            'password' => 'y$kHZBnAAppOfXP4GrHegquOw12ooCcT8bDOi0V1kkM8X/GYLNnW7uC', // secret,
            'category_id' => 27,
            'image' => 'uploads/NH13.jpg',
            'active' => 1
        ]);
        Restaurant::create([
            'name' => "Domino's Pizza",
            'slug' => 'dominos-pizza',
            'email' => 'dominospizza@contactus.com',
            'phone' => '514845555',
            'password' => 'y$kHZBnAAppOfXP4GrHegquOw12ooCcT8bDOi0V1kkM8X/GYLNnW7uC', // secret,
            'category_id' => 14,
            'image' => 'uploads/NH14.jpg',
            'active' => 1
        ]);
        Restaurant::create([
            'name' => 'La Shish',
            'slug' => 'la-shish',
            'email' => 'lashishairlie@gmail.com',
            'phone' => '4383337475',
            'password' => 'y$kHZBnAAppOfXP4GrHegquOw12ooCcT8bDOi0V1kkM8X/GYLNnW7uC', // secret,
            'category_id' => 22,
            'image' => 'uploads/NH15.jpg',
            'active' => 1
        ]);
        Restaurant::create([
            'name' => 'Tacos Frida',
            'slug' => 'tacos-frida',
            'email' => 'tacosfrida@mtl.com',
            'phone' => '5145482061',
            'password' => 'y$kHZBnAAppOfXP4GrHegquOw12ooCcT8bDOi0V1kkM8X/GYLNnW7uC', // secret,
            'category_id' => 26,
            'image' => 'uploads/NH16.jpg',
            'active' => 1
        ]);
        Restaurant::create([
            'name' => 'La Belle Province',
            'slug' => 'la-belle-province',
            'email' => 'labellepro@restaurant.ca',
            'phone' => '5143631305',
            'password' => 'y$kHZBnAAppOfXP4GrHegquOw12ooCcT8bDOi0V1kkM8X/GYLNnW7uC', // secret,
            'category_id' => 20,
            'image' => 'uploads/NH17.jpg',
            'active' => 1
        ]);
        Restaurant::create([
            'name' => 'Lafleur',
            'slug' => 'lafleur',
            'email' => 'restolafleur@info.ca',
            'phone' => '4506560005',
            'password' => 'y$kHZBnAAppOfXP4GrHegquOw12ooCcT8bDOi0V1kkM8X/GYLNnW7uC', // secret,
            'category_id' => 20,
            'image' => 'uploads/NH18.jpg',
            'delivery_fee' => '2.00',
            'active' => 1
        ]);
        Restaurant::create([
            'name' => 'Sushi Plus',
            'slug' => 'sushi-plus',
            'email' => 'sushiplus@mtl.ca',
            'phone' => '5147391888',
            'password' => 'y$kHZBnAAppOfXP4GrHegquOw12ooCcT8bDOi0V1kkM8X/GYLNnW7uC', // secret,
            'category_id' => 15,
            'image' => 'uploads/NH19.jpg',
            'active' => 1
        ]);
        Restaurant::create([
            'name' => 'Marathon Souvlaki',
            'slug' => 'marathon-souvlaki',
            'email' => 'marathon@souvlaki.ca',
            'phone' => '5147316455',
            'password' => 'y$kHZBnAAppOfXP4GrHegquOw12ooCcT8bDOi0V1kkM8X/GYLNnW7uC', // secret,
            'category_id' => 8,
            'image' => 'uploads/NH20.jpg',
            'active' => 1
        ]);
        Restaurant::create([
            'name' => 'O Fuzion',
            'slug' => 'o-fuzion',
            'email' => 'ofuzion@mtl.ca',
            'phone' => '5147271889',
            'password' => 'y$kHZBnAAppOfXP4GrHegquOw12ooCcT8bDOi0V1kkM8X/GYLNnW7uC', // secret,
            'category_id' => 25,
            'image' => 'uploads/NH21.jpg',
            'delivery_fee' => '2.75',
            'active' => 1
        ]);
        Restaurant::create([
            'name' => 'Tabla Village',
            'slug' => 'tabla-village',
            'email' => 'tablavillage@gmail.com',
            'phone' => '5145236464',
            'password' => 'y$kHZBnAAppOfXP4GrHegquOw12ooCcT8bDOi0V1kkM8X/GYLNnW7uC', // secret,
            'category_id' => 27,
            'image' => 'uploads/NH22.jpg',
            'active' => 1
        ]);
        Restaurant::create([
            'name' => 'Heirloom Pizzeria',
            'slug' => 'heirloom-pizzeria',
            'email' => 'pizzeriaheirloom@gmail.com',
            'phone' => '5149058211',
            'password' => 'y$kHZBnAAppOfXP4GrHegquOw12ooCcT8bDOi0V1kkM8X/GYLNnW7uC', // secret,
            'category_id' => 7,
            'image' => 'uploads/NH23.jpg',
            'active' => 1
        ]);
        Restaurant::create([
            'name' => 'La Banquise',
            'slug' => 'la-banquise',
            'email' => 'labanquise@poutine.ca',
            'phone' => '5145252415',
            'password' => 'y$kHZBnAAppOfXP4GrHegquOw12ooCcT8bDOi0V1kkM8X/GYLNnW7uC', // secret,
            'category_id' => 11,
            'image' => 'uploads/NH24.jpg',
            'delivery_fee' => '2.99',
            'active' => 1
        ]);
        Restaurant::create([
            'name' => 'Aux Vivres',
            'slug' => 'aux-vivres',
            'email' => 'info@auxvivres.ca',
            'phone' => '5145431267',
            'password' => 'y$kHZBnAAppOfXP4GrHegquOw12ooCcT8bDOi0V1kkM8X/GYLNnW7uC', // secret,
            'category_id' => 13,
            'image' => 'uploads/NH25.jpg',
            'active' => 1
        ]);
        Restaurant::create([
            'name' => 'Kwizinn Creole',
            'slug' => 'kwizinn-creole',
            'email' => 'kwizinnexpress@info.ca',
            'phone' => '5143796670',
            'password' => 'y$kHZBnAAppOfXP4GrHegquOw12ooCcT8bDOi0V1kkM8X/GYLNnW7uC', // secret,
            'category_id' => 28,
            'image' => 'uploads/NH26.jpg',
            'active' => 1
        ]);
        Restaurant::create([
            'name' => 'Firegrill',
            'slug' => 'firegrill',
            'email' => 'firegrill@gmail.com',
            'phone' => '5148320222',
            'password' => 'y$kHZBnAAppOfXP4GrHegquOw12ooCcT8bDOi0V1kkM8X/GYLNnW7uC', // secret,
            'category_id' => 21,
            'image' => 'uploads/NH27.jpg',
            'delivery_fee' => '4.01',
            'active' => 1
        ]);
        Restaurant::create([
            'name' => 'Mandys',
            'slug' => 'mandys',
            'email' => 'salademandys@info.ca',
            'phone' => '5144190779',
            'password' => 'y$kHZBnAAppOfXP4GrHegquOw12ooCcT8bDOi0V1kkM8X/GYLNnW7uC', // secret,
            'category_id' => 10,
            'image' => 'uploads/NH28.jpg',
            'active' => 1
        ]);
        Restaurant::create([
            'name' => 'Cacao 70',
            'slug' => 'cacao-70',
            'email' => 'cacao70@eatery.com',
            'phone' => '5148444442',
            'password' => 'y$kHZBnAAppOfXP4GrHegquOw12ooCcT8bDOi0V1kkM8X/GYLNnW7uC', // secret,
            'category_id' => 5,
            'image' => 'uploads/NH29.jpg',
            'active' => 1
        ]);
        Restaurant::create([
            'name' => 'Peddlers',
            'slug' => 'peddlers',
            'email' => 'peddlers@restaurant.ca',
            'phone' => '5143647204',
            'password' => 'y$kHZBnAAppOfXP4GrHegquOw12ooCcT8bDOi0V1kkM8X/GYLNnW7uC', // secret,
            'category_id' => 4,
            'image' => 'uploads/NH30.jpg',
            'active' => 1
        ]);
        Restaurant::create([
            'name' => 'Sunset Diner',
            'slug' => 'sunset-diner',
            'email' => 'sunset@restaurant.ca',
            'phone' => '5143647205',
            'password' => 'y$kHZBnAAppOfXP4GrHegquOw12ooCcT8bDOi0V1kkM8X/GYLNnW7uC', // secret,
            'category_id' => 2,
            'image' => 'uploads/NH31.jpg',
            'active' => 1
        ]);

        Restaurant::create([
            'name' => 'Ocean Grill',
            'slug' => 'ocean-grill',
            'email' => 'ocean@restaurant.ca',
            'phone' => '5143647206',
            'password' => 'y$kHZBnAAppOfXP4GrHegquOw12ooCcT8bDOi0V1kkM8X/GYLNnW7uC', // secret,
            'category_id' => 3,
            'image' => 'uploads/NH32.jpg',
            'active' => 1
        ]);

        Restaurant::create([
            'name' => 'Golden Spoon',
            'slug' => 'golden-spoon',
            'email' => 'golden@restaurant.ca',
            'phone' => '5143647207',
            'password' => 'y$kHZBnAAppOfXP4GrHegquOw12ooCcT8bDOi0V1kkM8X/GYLNnW7uC', // secret,
            'category_id' => 1,
            'image' => 'uploads/NH33.jpg',
            'active' => 1
        ]);

        Restaurant::create([
            'name' => 'La Piazza',
            'slug' => 'la-piazza',
            'email' => 'lapiazza@restaurant.ca',
            'phone' => '5143647208',
            'password' => 'y$kHZBnAAppOfXP4GrHegquOw12ooCcT8bDOi0V1kkM8X/GYLNnW7uC', // secret,
            'category_id' => 4,
            'image' => 'uploads/NH34.jpg',
            'active' => 1
        ]);

        Restaurant::create([
            'name' => 'Sakura Garden',
            'slug' => 'sakura-garden',
            'email' => 'sakura@restaurant.ca',
            'phone' => '5143647209',
            'password' => 'y$kHZBnAAppOfXP4GrHegquOw12ooCcT8bDOi0V1kkM8X/GYLNnW7uC', // secret,
            'category_id' => 5,
            'image' => 'uploads/NH35.jpg',
            'active' => 1
        ]);

        Restaurant::create([
            'name' => 'Curry House',
            'slug' => 'curry-house',
            'email' => 'curryhouse@restaurant.ca',
            'phone' => '5143647210',
            'password' => 'y$kHZBnAAppOfXP4GrHegquOw12ooCcT8bDOi0V1kkM8X/GYLNnW7uC', // secret,
            'category_id' => 6,
            'image' => 'uploads/NH36.jpg',
            'active' => 1
        ]);

        Restaurant::create([
            'name' => 'Urban Eats',
            'slug' => 'urban-eats',
            'email' => 'urban@restaurant.ca',
            'phone' => '5143647211',
            'password' => 'y$kHZBnAAppOfXP4GrHegquOw12ooCcT8bDOi0V1kkM8X/GYLNnW7uC', // secret,
            'category_id' => 3,
            'image' => 'uploads/NH37.jpg',
            'active' => 1
        ]);
    }
}