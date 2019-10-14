<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Laratube\User;
use Laratube\Model;
use Laratube\Video;
use Laratube\Comment;
use Faker\Generator as Faker;

$factory->define(Comment::class, function (Faker $faker) {
    return [
        'body' => $faker->sentence(6),
        'user_id'=> function() {
            return factory(User::class)->create()->id;
        },
        'video_id'=> function() {
            return factory(Video::class)->create()->id;
        },
        'comment_id' => null
    ];
});
