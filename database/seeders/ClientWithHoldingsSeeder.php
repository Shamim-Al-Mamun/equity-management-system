<?php

// database/seeders/ClientWithHoldingsSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Client;
use App\Models\ClientHolding;

class ClientWithHoldingsSeeder extends Seeder
{
    public function run(): void
    {
        Client::factory()
            ->count(50)
            ->create()
            ->each(function ($client) {
                // Create 1–5 holdings per client
                $holdings = ClientHolding::factory()
                    ->count(rand(1, 5))
                    ->make();

                $client->holdings()->saveMany($holdings);
            });
    }
}
