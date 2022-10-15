<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Coadebit>
 */
class CoadebitFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->words(3, true),
            'kode' => $this->faker->randomFloat(3, 10000, 11000),
            'cabang_id' => $this->faker->numberBetween(1,4),
            'laporan' => 'NR',
        ];
    }
}
