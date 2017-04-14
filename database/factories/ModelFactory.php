<?php

use Faker\Generator;
use App\Models\Access\Role\Role;
use App\Models\Access\User\User;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(User::class, function (Generator $faker) {
    static $password;

    return [
        'name'              => $faker->name,
        'email'             => $faker->safeEmail,
        'password'          => $password ?: $password = bcrypt('secret'),
        'remember_token'    => str_random(10),
        'confirmation_code' => md5(uniqid(mt_rand(), true)),
    ];
});

$factory->state(User::class, 'active', function () {
    return [
        'status' => 1,
    ];
});

$factory->state(User::class, 'inactive', function () {
    return [
        'status' => 0,
    ];
});

$factory->state(User::class, 'confirmed', function () {
    return [
        'confirmed' => 1,
    ];
});

$factory->state(User::class, 'unconfirmed', function () {
    return [
        'confirmed' => 0,
    ];
});

/*
 * Roles
 */
$factory->define(Role::class, function (Generator $faker) {
    return [
        'name' => $faker->name,
        'all'  => 0,
        'sort' => $faker->numberBetween(1, 100),
    ];
});

$factory->state(Role::class, 'admin', function () {
    return [
        'all' => 1,
    ];
});


/**
 * Posts
 */
$factory->define(App\Post::class, function (Faker\Generator $faker) {
    $html_content = join("\n\n", $faker->paragraphs(mt_rand(7, 20)));
    return [
        'title' => $faker->sentence(mt_rand(5, 10)),
        'content' => $html_content,
        'html_content' => $html_content,
        'slug' => $faker->slug(),
        'published_at' => $faker->dateTime,
        'description' => $faker->sentence(mt_rand(5, 15)),
        'status' => 1,
        /*'category_id' => function () {
            return factory(App\Category::class)->create()->id;
        },*/
    ];
});

$factory->define(App\Category::class, function (Faker\Generator $faker) {
    return [
        'name' => str_random(7),
    ];
});

$factory->define(App\Tag::class, function (Faker\Generator $faker) {
    return [
        'name' => str_random(7),
    ];
});
