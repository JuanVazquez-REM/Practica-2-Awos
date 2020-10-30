<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Persona;
use Faker\Generator as Faker;

$factory->define(App\Persona::class, function (Faker $faker) {
    return [
        'nombre' => $faker->firstNameMale(),
        'apellidos' => $faker->lastName(),
        'email' => $faker->unique()->safeEmail,
        'edad' => $faker->numberBetween(16,40),
        'genero' => $faker->randomElement(['femenino', 'masculino']),
        
    ];
}); 
