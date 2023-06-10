<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Frontoffice>
 */
class FrontofficeFactory extends Factory
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
            'penyetor' => $this->faker->name(),
            'penerima' => $this->faker->name(),
            'nobuktipenerima' => $this->faker->numerify('####BRV-FO'),
            'tanggal' => $this->faker->dateTimeBetween('-2 week'),
            'ref' => 'JKSR',
            'jumlah' => $this->faker->randomNumber(5, true),
            'tempatbayar' => 'kantor',
            'coadebit_id' => $this->faker->numberBetween(1, 20),
            'coakredit_id' => $this->faker->numberBetween(1, 20),
            'cabang_id' => $this->faker->numberBetween(1,4),
            'jenis_data' => $this->faker->numberBetween(0,1),
            ];
    }
}
