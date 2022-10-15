<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Kasbesar>
 */
class KasbesarFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->sentence(3),
            'penerima' => $this->faker->name(),
            'nobuktikas' => $this->faker->numerify('####CDV-OH'),
            'tanggal' => $this->faker->date(),
            'ref' => 'JKBB',
            'jumlah' => $this->faker->randomNumber(5, true),
            'coadebit_id' => $this->faker->numberBetween(1, 20),
            'coakredit_id' => $this->faker->numberBetween(1, 20),
            'cabang_id' => $this->faker->numberBetween(1,4),
            ];
    }
}
