<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Country;

/**
 * @template TModel of \App\Models\Country
 *
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<TModel>
 */
class CountryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<TModel>
     */
    protected $model = Country::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->country,
            'cc' => $this->faker->lexify('AA'),
            'cc2' => $this->faker->lexify('AAA'),
            'feature_class' => $this->faker->word,
            'feature_code' => $this->faker->word,
            'latitude' => $this->faker->latitude,
            'longitude' => $this->faker->longitude,
        ];
    }
}
