<?php

namespace database\factories;

use App\Models\State;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @template TModel of \App\Models\State
 *
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<TModel>
 */
class StateFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<TModel>
     */
    protected $model = State::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->country,
            'feature_class' => $this->faker->word,
            'feature_code' => $this->faker->word,
        ];
    }
}
