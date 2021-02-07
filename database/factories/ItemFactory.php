<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Item;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Item::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'price' => 500.48,
        'quantity' => 3,
        'sku' => Str::random(6) . '3456',
        'description' => $faker->text
    ];
});
