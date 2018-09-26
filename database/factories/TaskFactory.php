<?php
/**
 * @copyright maki fujiwara <makies@gmail.com>
 */

use Faker\Generator as Faker;

$factory->define(App\Models\Task::class, function (Faker $faker) {
    return [
        'title' => $faker->userName,
        'body' => $faker->realText(),
        'deleted_at' => $faker->randomElement([
            null,
            null,
            null,
            null,
            date('Y-m-d H:i:s'),
        ]),
    ];
});
