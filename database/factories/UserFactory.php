<?php

/**
 * Script that generates random Users and stores them into the users table
 */

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

$factory->define(App\User::class, function (Faker $faker) {

    $now = $faker->unixTime();
    $faker->seed($now);

    return [
        'first_name'        => $faker->firstName,
        'last_name'         => $faker->lastName,
        'username'          => $faker->unique()->userName . $faker->randomNumber(),
        'email'             => $faker->unique()->safeEmail,
        'password'          => bcrypt('Password'),
        'is_admin'          => 0,
        'remember_token'    => str_random(10),
        'created_at'        => date("Y-m-d H:i:s", $now),
        'updated_at'        => date("Y-m-d H:i:s", $now)
    ];
});
