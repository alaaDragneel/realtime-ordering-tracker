<?php

use Faker\Generator as Faker;

$factory->define(\App\Order::class, function (Faker $faker) {

    return [
        'user_id' => function () {
            return create('App\User')->id;
        },
        'address' => $faker->sentence,
        'size' => $faker->word,
        'toppings' => implode(', ', [$faker->word, $faker->word]),
        'instructions' => $faker->word,
        'status_id'=> function () {
            return create('App\Status')->id;
        },
    ];
});
