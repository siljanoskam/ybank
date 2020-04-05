<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Transaction;
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

$factory->define(Transaction::class, function (Faker $faker) {
    return [
        'from' => $faker->numberBetween(1, 2),
        'to' => $faker->numberBetween(1, 2),
        'details' => $faker->sentence,
        // we're setting the range of the transactions amount to max=20000
        // so it doesn't exceed their possible account balance (which goes from 20000 to 50000)
        'amount' => $faker->numberBetween(1, 10000)
    ];
});
