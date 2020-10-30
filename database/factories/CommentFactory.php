<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Comment;
use Faker\Generator as Faker;

$factory->define(Comment::class, function (Faker $faker) {
    return [
        'post_id'=> $faker->numberBetween(1,20),
        'nombre' => $faker->title,
        'user_id' => $faker->numberBetween(1,15),
        'contenido' => $faker->paragraph,
    ];
});
