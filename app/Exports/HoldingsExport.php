<?php

namespace App\Exports;

use App\Models\ClientHolding;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Http\Request;

class HoldingsExport implements FromView
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function view(): View
    {
        $query = ClientHolding::with('client');

        if ($this->request->has('client_id')) {
            $query->where('client_id', $this->request->client_id);
        }

        if ($this->request->has('sector')) {
            $query->where('sector', $this->request->sector);
        }

        if ($this->request->has('from') && $this->request->has('to')) {
            $query->whereBetween('created_at', [$this->request->from, $this->request->to]);
        }

        return view('exports.holdings', ['holdings' => $query->get()]);
    }
}
