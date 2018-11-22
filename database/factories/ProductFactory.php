<?php

use App\User;
use Faker\Generator as Faker;

$factory->define(App\Product::class, function (Faker $faker) {
    return [
        'name'=>$faker->word,
        'description'=>$faker->paragraph(1),
        'quantity'=>$faker->numberBetween(1,10),
        'status'=>$faker->randomElement([\App\Product::PRODUCTO_DISPONIBLE,\App\Product::PRODUCTO_NO_DISPONIBLE]),
        'image'=>$faker->randomElement(['1.jpg','2.jpg','3.jpg']),
        'seller_id'=>User::all()->random()->id,
    ];
});
