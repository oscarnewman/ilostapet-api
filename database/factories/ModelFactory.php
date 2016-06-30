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

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->firstName,
        'email' => $faker->safeEmail,
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Pet::class, function (Faker\Generator $faker) {
    return [
        'user_id' => function() {
            return factory(App\User::class)->create()->id;
        },
        'animal'        => randOption(['dog', 'cat']),
        'gender'        => randOption(['male', 'female']),
        'name'          => randOption([NULL, NULL, $faker->firstName]),
        'breed'         => randOption(["Labrador Retriever", "German Shepherd", "Golden Retriever", "Yorkshire Terrier", "Bulldog", "Beagle", "Chihuahua", NULL, NULL]),
        'description'   => $faker->realText($maxNbChars = 300),
        'has_collar'    => $faker->boolean(),
        'has_tags'      => $faker->boolean(),
        'has_microchip' => $faker->boolean(),
    ];
});

$factory->define(App\Post::class, function (Faker\Generator $faker) use ($factory) {
    return [
        'pet_id' => function() {
            return factory(App\Pet::class)->create()->id;
        },
        'type'          => randOption(['lost', 'found']),
        'address' => $faker->address,
        'lat' => $faker->latitude,
        'lng' => $faker->longitude,
    ];
});

$factory->define(App\Image::class, function (Faker\Generator $faker) {
    return [
        'image_url' => $faker->imageURL(800, 600, 'cats'),
        'width'     => 800,
        'height'   => 600,
    ];
});

$factory->define(App\ContactInfo::class, function ($faker) {
    return [
        'value' => $faker->phoneNumber,
    ];
});

function randOption($array) {
    return $array[array_rand($array)];
}
