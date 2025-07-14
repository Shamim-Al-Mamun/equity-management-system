<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ClientHolding;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ClientHoldingsImport;
use App\Jobs\UpdateStockPricesJob;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="ClientHolding",
 *     type="object",
 *     required={"client_id", "stock_symbol", "quantity", "purchase_price"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="client_id", type="integer", example=1),
 *     @OA\Property(property="stock_symbol", type="string", example="DSEXYZ"),
 *     @OA\Property(property="sector", type="string", example="Dhaka"),
 *     @OA\Property(property="quantity", type="integer", example=100),
 *     @OA\Property(property="purchase_price", type="number", format="float", example=75.50),
 *     @OA\Property(property="current_price", type="number", format="float", example=50.50),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-01T00:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-01-01T00:00:00Z")
 * )
 *
 * @OA\Tag(
 *     name="ClientHoldings",
 *     description="Client Holding CRUD & Import"
 * )
 */
class ClientHoldingController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/client-holdings",
     *     summary="List all client holdings",
     *     tags={"ClientHoldings"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of client holdings",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/ClientHolding"))
     *     )
     * )
     */
    public function index()
    {
        $holdings = ClientHolding::with('client')->latest()->paginate(10);
    
        return response()->json([
            'status' => true,
            'message' => 'Client holdings retrieved successfully',
            'data' => $holdings
        ], 200); // HTTP OK
    }
    

    /**
     * @OA\Post(
     *     path="/api/client-holdings",
     *     summary="Create a new holding for a client",
     *     tags={"ClientHoldings"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"client_id", "stock_symbol", "quantity", "purchase_price"},
     *             @OA\Property(property="client_id", type="integer", example=1),
     *             @OA\Property(property="stock_symbol", type="string", example="DSEXYZ"),
     *             @OA\Property(property="quantity", type="integer", example=100),
     *             @OA\Property(property="purchase_price", type="number", example=75.50)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Holding created",
     *         @OA\JsonContent(ref="#/components/schemas/ClientHolding")
     *     )
     * )
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'client_id'      => 'required|exists:clients,id',
            'stock_symbol'   => 'required|string',
            'quantity'       => 'required|integer|min:1',
            'purchase_price' => 'required|numeric|min:0',
            'sector'         => 'nullable|string', // optional sector
        ]);
    
        $holding = ClientHolding::create($data);
    
        return response()->json([
            'status' => true,
            'message' => 'Client holding created successfully',
            'data' => $holding
        ], 201); // 201 = Created
    }
    

    /**
     * @OA\Get(
     *     path="/api/client-holdings/{id}",
     *     summary="Get a specific client holding",
     *     tags={"ClientHoldings"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id", in="path", required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Holding found",
     *         @OA\JsonContent(ref="#/components/schemas/ClientHolding")
     *     ),
     *     @OA\Response(response=404, description="Not Found")
     * )
     */
    public function show(ClientHolding $clientHolding)
    {
        $clientHolding->load('client');
    
        return response()->json([
            'status' => true,
            'message' => 'Client holding retrieved successfully',
            'data' => $clientHolding
        ], 200);
    }    

    /**
     * @OA\Put(
     *     path="/api/client-holdings/{id}",
     *     summary="Update a client holding",
     *     tags={"ClientHoldings"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id", in="path", required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="client_id", type="integer", example=1),
     *             @OA\Property(property="stock_symbol", type="string", example="DSEXYZ"),
     *             @OA\Property(property="quantity", type="integer", example=100),
     *             @OA\Property(property="purchase_price", type="number", example=75.50)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Holding updated",
     *         @OA\JsonContent(ref="#/components/schemas/ClientHolding")
     *     )
     * )
     */
    public function update(Request $request, ClientHolding $clientHolding)
    {
        $data = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'stock_symbol' => 'required|string',
            'quantity' => 'required|integer|min:1',
            'purchase_price' => 'required|numeric|min:0',
            'sector' => 'nullable|string', // optional sector update
        ]);
    
        $clientHolding->update($data);
    
        return response()->json([
            'status' => true,
            'message' => 'Client holding updated successfully',
            'data' => $clientHolding
        ], 200);
    }
    

    /**
     * @OA\Delete(
     *     path="/api/client-holdings/{id}",
     *     summary="Delete a client holding",
     *     tags={"ClientHoldings"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id", in="path", required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=204, description="Deleted")
     * )
     */
    public function destroy(ClientHolding $clientHolding)
    {
        $clientHolding->delete();
    
        return response()->json([
            'status' => true,
            'message' => 'Client holding deleted successfully',
            'data' => null
        ], 200); // OK
    }
    

    /**
     * @OA\Post(
     *     path="/api/client-holdings/import",
     *     summary="Import client holdings from Excel or CSV",
     *     tags={"ClientHoldings"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"file"},
     *                 @OA\Property(
     *                     description="Excel or CSV file",
     *                     property="file",
     *                     type="string",
     *                     format="binary"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Import successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Import successful")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation failed or file format error",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="File format error: Invalid file format")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Import failed due to other errors",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Import failed: Unexpected error message")
     *         )
     *     )
     * )
     */

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,csv',
        ]);
    
        try {
            Excel::import(new ClientHoldingsImport, $request->file('file'));
            return response()->json([
                'status' => true,
                'message' => 'Import successful'
            ], 200);
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            // Handles validation errors during import
            return response()->json([
                'status' => false,
                'message' => 'File format error: ' . $e->getMessage(),
            ], 422);
        } catch (\Exception $e) {
            // Handles any other exceptions
            return response()->json([
                'status' => false,
                'message' => 'Import failed: ' . $e->getMessage(),
            ], 400);
        }
    }


        /**
     * @OA\Post(
     *     path="/api/client-holdings/update-prices",
     *     summary="Manually trigger update of current prices for holdings",
     *     tags={"ClientHoldings"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Price update job dispatched",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Price update job dispatched successfully")
     *         )
     *     )
     * )
     */
    public function updatePrices()
    {
        UpdateStockPricesJob::dispatch();
        return response()->json(['message' => 'Price update job dispatched successfully']);
    }
    
}
