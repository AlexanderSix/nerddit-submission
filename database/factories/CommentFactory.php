<?php

/**
 * Script that generates random Comments and stores them into the comments table
 */

use Faker\Generator as Faker;

$factory->define(App\Comment::class, function (Faker $faker) {

    $now = $faker->unixTime();
    $faker->seed($now);

    $PDO_query = DB::connection()->getPdo()->query(
        "SELECT * FROM `posts`"
    );

    $number_of_posts = $PDO_query->rowCount();

    $PDO_query = DB::connection()->getPdo()->query(
        "SELECT * FROM `users`"
    );

    $number_of_users = $PDO_query->rowCount();

    $content = $faker->realText(rand(100, 500), rand(1, 5));
    $content = str_replace(["'", '"', "(", ")", "\n"], ["''", '""', "\(", "\)", "\\n"], $content);

    return [
        'user_id'       => rand(1, $number_of_users),
        'post_id'       => rand(1, $number_of_posts),
        'content'       => $content,
        'created_at'    => date("Y-m-d H:i:s", $now),
        'updated_at'    => date("Y-m-d H:i:s", $now)
    ];
});
