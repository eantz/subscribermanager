<?php

use Faker\Generator as Faker;

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

$factory->define(App\UserField::class, function (Faker $faker) {
    $title = $faker->name;

    return [
        'name' => strtolower(preg_replace("/[^A-Za-z0-9]/", '_', $title)),
        'title' => $title,
        'type' => 'string'
    ];
});
