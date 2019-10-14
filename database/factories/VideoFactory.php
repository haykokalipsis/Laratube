<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Laratube\Model;
use Laratube\Video;
use Laratube\Channel;
use Faker\Generator as Faker;

$factory->define(Video::class, function (Faker $faker) {
    return [
        'channel_id'  => function() {
            return factory(Channel::class)->create()->id;
        },
        'views'       => $faker->numberBetween(1, 1000),
        'title'       => $faker->sentence(4),
        'thumbnail'   => $faker->imageUrl(),
        'description' => $faker->sentence(10),
        'path'        => $faker->word(),
        'percentage'  => 100
    ];
});
