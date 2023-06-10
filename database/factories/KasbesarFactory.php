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
            'tanggal' => $this->faker->dateTimeBetween('-2 week'),
            'ref' => 'JKBB',
            'jumlah' => $this->faker->randomNumber(5, true),
            'coadebit_id' => 380,
            'coakredit_id' => 1,
            'cabang_id' => 1,
            'jenis_data' => $this->faker->numberBetween(0,1),
            ];
    }
}
