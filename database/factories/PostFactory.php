<?php

/**
 * Script that generates random Posts and stores them into the posts table
 */

use Faker\Generator as Faker;

$factory->define(App\Post::class, function (Faker $faker) {
    $now = $faker->unixTime();
    $faker->seed($now);

    $PDO_query = DB::connection()->getPdo()->query(
        "SELECT * FROM `users`"
    );

    $number_of_users = $PDO_query->rowCount();

    $title = $faker->realText(rand(15,191), 1);
    $title = str_replace(["'", '"', "(", ")", "\n"], ["''", '""', "\(", "\)", "\\n"], $title);

    $content = $faker->realText(rand(100, 500), rand(1, 5));
    $content = str_replace(["'", '"', "(", ")", "\n"], ["''", '""', "\(", "\)", "\\n"], $content);

    $link = $faker->url;

    if (rand(1, 100) > 50) {
        $link = null;
    }

    return [
        'user_id'       => rand(1, $number_of_users),
        'title'         => $title,
        'content'       => $content,
        'link'          => $link,
        'likes'         => rand((-1 * $number_of_users), $number_of_users),
        'created_at'    => date("Y-m-d H:i:s", $now),
        'updated_at'    => date("Y-m-d H:i:s", $now)
    ];
});
