<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

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

$factory->define(User::class, function (Faker $faker) {
    return [
        'status'    => 1,
        'usercode'  => \Illuminate\Support\Str::random(5),
        'userpwd'   => hash('sha256','123456'),
        'name'=>$faker->name,
        'sex'       => \Illuminate\Support\Arr::random([0,1]),
        'birthdate' => date('Y-m-d', time()),
        'idno'      => '500381' . rand(100000000000, 999999999999),
        'tel'       => rand(10000000000, 99999999999),
        'email'     => $faker->email,
        'adress'    => $faker->address,
        'api_token' => hash('sha256', \Illuminate\Support\Str::random(16)),
        'province'  => 1,
        'city'      => 2,
        'district'  => 3,
        'adduserid' => 1,
        'addtime'   => now()
    ];
});
