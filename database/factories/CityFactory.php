<?php

namespace database\factories;

use App\Models\City;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @template TModel of \App\Models\City
 *
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<TModel>
 */
class CityFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<TModel>
     */
    protected $model = City::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->city,
            'latitude' => $this->faker->latitude,
            'longitude' => $this->faker->longitude,
            'feature_class' => $this->faker->word,
            'feature_code' => $this->faker->word,
            'admin1' => $this->faker->word,
            'admin2' => $this->faker->word,
            'admin3' => $this->faker->word,
            'admin4' => $this->faker->word,
            'population' => $this->faker->numberBetween(1000,10000000),
            'elevation' => $this->faker->numberBetween(-100,6000),
            'timezone' => $this->faker->timezone,
            'updated_at' => $this->faker->dateTimeBetween('-1 month'),
        ];
    }
}
