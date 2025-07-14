<?php

// database/factories/ClientHoldingFactory.php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ClientHoldingFactory extends Factory
{
    public function definition(): array
    {
        return [
            'stock_symbol'   => strtoupper($this->faker->lexify('???')),
            'quantity'       => $this->faker->numberBetween(10, 500),
            'sector'         => $this->faker->randomElement(['Finance', 'Energy', 'Tech', 'Healthcare']),
            'purchase_price' => $this->faker->randomFloat(2, 10, 1000),
            'current_price'  => $this->faker->randomFloat(2, 10, 1000),
        ];
    }
}
