<?php

namespace App\Jobs;

use App\Models\ClientHolding;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateStockPricesJob implements ShouldQueue
{
    use Dispatchable, Queueable;

    public function handle()
    {
        // Mock API response
        $mockPrices = [
            'IDO ' => 101.25,
            'IDO' => 82.00,
            'ZEK' => 71.30,
        ];

        $holdings = ClientHolding::all();

        foreach ($holdings as $holding) {
            $symbol = $holding->stock_symbol;

            if (isset($mockPrices[$symbol])) {
                $holding->update([
                    'current_price' => $mockPrices[$symbol],
                ]);
            }
        }
    }
}
