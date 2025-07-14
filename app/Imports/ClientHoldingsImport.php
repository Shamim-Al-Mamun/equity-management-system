<?php

namespace App\Imports;

use App\Models\ClientHolding;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ClientHoldingsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new ClientHolding([
            'client_id' => $row['client_id'],
            'stock_symbol' => $row['stock_symbol'],
            'quantity' => $row['quantity'],
            'purchase_price' => $row['purchase_price'],
        ]);
    }
}
