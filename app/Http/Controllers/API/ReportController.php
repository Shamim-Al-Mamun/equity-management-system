<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\ClientHolding;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\HoldingsExport;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Reports",
 *     description="Equity summary reports for clients and sectors"
 * )
 */


class ReportController extends Controller
{
        /**
     * @OA\Get(
     *     path="/api/reports/client",
     *     summary="Get client/sector-wise equity report",
     *     tags={"Reports"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="client_id", in="query", required=false, @OA\Schema(type="integer")),
     *     @OA\Parameter(name="sector", in="query", required=false, @OA\Schema(type="string")),
     *     @OA\Parameter(name="from", in="query", required=false, @OA\Schema(type="string", format="date")),
     *     @OA\Parameter(name="to", in="query", required=false, @OA\Schema(type="string", format="date")),
     *     @OA\Response(
     *         response=200,
     *         description="Filtered client holdings report",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="stock_symbol", type="string", example="DSEXYZ"),
     *                 @OA\Property(property="sector", type="string", example="Tech"),
     *                 @OA\Property(property="quantity", type="integer", example=100),
     *                 @OA\Property(property="purchase_price", type="number", example=75.5),
     *                 @OA\Property(property="client", type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="John Doe")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */


    public function clientReport(Request $request)
    {
        $query = ClientHolding::with('client');

        if ($request->has('client_id')) {
            $query->where('client_id', $request->client_id);
        }

        if ($request->has('sector')) {
            $query->where('sector', $request->sector);
        }

        if ($request->has('from') && $request->has('to')) {
            $query->whereBetween('created_at', [$request->from, $request->to]);
        }

        $data = $query->get();

        return response()->json($data);
    }

        /**
     * @OA\Get(
     *     path="/api/reports/export-excel",
     *     summary="Export holdings report as Excel",
     *     tags={"Reports"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="client_id", in="query", required=false, @OA\Schema(type="integer")),
     *     @OA\Parameter(name="sector", in="query", required=false, @OA\Schema(type="string")),
     *     @OA\Parameter(name="from", in="query", required=false, @OA\Schema(type="string", format="date")),
     *     @OA\Parameter(name="to", in="query", required=false, @OA\Schema(type="string", format="date")),
     *     @OA\Response(response=200, description="Excel file downloaded"),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */


    public function exportExcel(Request $request)
    {
        return Excel::download(new HoldingsExport($request), 'holdings.xlsx');
    }

        /**
     * @OA\Get(
     *     path="/api/reports/export-pdf",
     *     summary="Export holdings report as PDF",
     *     tags={"Reports"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="client_id", in="query", required=false, @OA\Schema(type="integer")),
     *     @OA\Parameter(name="sector", in="query", required=false, @OA\Schema(type="string")),
     *     @OA\Parameter(name="from", in="query", required=false, @OA\Schema(type="string", format="date")),
     *     @OA\Parameter(name="to", in="query", required=false, @OA\Schema(type="string", format="date")),
     *     @OA\Response(response=200, description="PDF downloaded"),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */


    public function exportPdf(Request $request)
    {
        $query = ClientHolding::with('client');

        if ($request->has('client_id')) {
            $query->where('client_id', $request->client_id);
        }

        if ($request->has('sector')) {
            $query->where('sector', $request->sector);
        }

        if ($request->has('from') && $request->has('to')) {
            $query->whereBetween('created_at', [$request->from, $request->to]);
        }

        $data = $query->get();

        $pdf = PDF::loadView('reports.holdings', ['holdings' => $data]);
        return $pdf->download('holdings.pdf');
    }
}
