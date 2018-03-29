<?php

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

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Eloquent\Office::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->sentence(2),
        'description' => $faker->text(200),
        'area' => $faker->sentence(2),
    ];
});

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Eloquent\User::class, function (Faker\Generator $faker) {
    static $password;
    static $officeId;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = '123456',
        'phone' => $faker->phoneNumber,
        'code' => str_random(7),
        'position' => $faker->word,
        'office_id' => $faker->randomElement($officeId ?: $officeId = \App\Eloquent\Office::pluck('id')->toArray()),
        'remember_token' => str_random(10),
    ];
});

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Eloquent\Category::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->sentence(4),
        'description' => $faker->text(200),
    ];
});

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Eloquent\Book::class, function (Faker\Generator $faker) {
    static $categoryId;
    static $officeId;

    return [
        'title' => $faker->sentence(6),
        'description' => $faker->text(200),
        'author' => $faker->name,
        'publish_date' => $faker->date('Y-m-d'),
        'total_page' => $faker->numberBetween(50, 100),
        'code' => str_random(50),
        'count_view' => $faker->numberBetween(20, 100),
        'office_id' => $faker->randomElement($officeId ?: $officeId = \App\Eloquent\Office::pluck('id')->toArray()),
        'category_id' => $faker->randomElement($categoryId ?: $categoryId = \App\Eloquent\Category::pluck('id')->toArray()),
    ];
});

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Eloquent\Suggestion::class, function (Faker\Generator $faker) {
    static $userId;
    static $categoryId;

    return [
        'title' => $faker->sentence(6),
        'description' => $faker->text(200),
        'user_id' => $faker->randomElement($userId ?: $userId = \App\Eloquent\User::pluck('id')->toArray()),
        'category_id' => $faker->randomElement($categoryId ?: $categoryId = \App\Eloquent\Category::pluck('id')->toArray()),
    ];
});

$factory->define(App\Eloquent\Notification::class, function (Faker\Generator $faker) {
    static $userSendId;
    static $userReceiveId;
    static $targetId;

    return [
        'user_send_id' => $faker->randomElement($userSendId ?: $userSendId = \App\Eloquent\User::pluck('id')->toArray()),
        'user_receive_id' => $faker->randomElement($userReceiveId ?: $userReceiveId = \App\Eloquent\User::pluck('id')->toArray()),
        'target_id' => $faker->randomElement($targetId ?: $targetId = \App\Eloquent\Book::pluck('id')->toArray()),
        'type' => config('model.notification.waiting'),
    ];
});
