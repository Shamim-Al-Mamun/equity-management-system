<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="Client",
 *     type="object",
 *     required={"name", "email"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Jane Doe"),
 *     @OA\Property(property="email", type="string", example="jane@example.com"),
 *     @OA\Property(property="phone", type="string", example="01812345678"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-01T00:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-01-01T00:00:00Z")
 * )
 *
 * @OA\Tag(
 *     name="Clients",
 *     description="Client CRUD Endpoints"
 * )
 */
class ClientController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/clients",
     *     summary="List all clients with holdings",
     *     tags={"Clients"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of clients",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Client"))
     *     )
     * )
     */
    public function index()
    {
        $clients = Client::with('holdings')->latest()->paginate(10); // 10 per page
    
        return response()->json([
            'status' => true,
            'message' => 'Clients retrieved successfully',
            'data' => $clients
        ], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/clients",
     *     summary="Create a new client",
     *     tags={"Clients"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email"},
     *             @OA\Property(property="name", type="string", example="Jane Doe"),
     *             @OA\Property(property="email", type="string", example="jane@example.com"),
     *             @OA\Property(property="phone", type="string", example="01812345678")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Client created",
     *         @OA\JsonContent(ref="#/components/schemas/Client")
     *     )
     * )
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:clients',
            'phone' => 'nullable|string',
        ]);

        // $client = Client::create($data);
        $client = Client::create($data);

        return response()->json([
            'status' => true,
            'message' => 'Client added successfully',
            'data' => $client
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/clients/{id}",
     *     summary="Get a single client with holdings",
     *     tags={"Clients"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Client found",
     *         @OA\JsonContent(ref="#/components/schemas/Client")
     *     ),
     *     @OA\Response(response=404, description="Client not found")
     * )
     */
    public function show(Client $client)
    {
        $client->load('holdings');
        return response()->json([
            'status' => true,
            'message' => 'Client retrieved successfully',
            'data' => $client
        ]);
    }    

    /**
     * @OA\Put(
     *     path="/api/clients/{id}",
     *     summary="Update a client",
     *     tags={"Clients"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email"},
     *             @OA\Property(property="name", type="string", example="Jane Doe"),
     *             @OA\Property(property="email", type="string", example="jane@example.com"),
     *             @OA\Property(property="phone", type="string", example="01812345678")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Client updated",
     *         @OA\JsonContent(ref="#/components/schemas/Client")
     *     )
     * )
     */
    public function update(Request $request, Client $client)
    {
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:clients,email,' . $client->id,
            'phone' => 'nullable|string',
        ]);
    
        $client->update($data);
    
        return response()->json([
            'status' => true,
            'message' => 'Client updated successfully',
            'data' => $client
        ], 200); // 200 OK
    }
    

    /**
     * @OA\Delete(
     *     path="/api/clients/{id}",
     *     summary="Delete a client",
     *     tags={"Clients"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Client deleted"
     *     )
     * )
     */
    public function destroy($id)
    {
        $client = Client::find($id);
    
        if (!$client) {
            return response()->json([
                'status' => false,
                'message' => 'Client not found',
                'data' => null
            ], 404);
        }
    
        $client->delete();
    
        return response()->json([
            'status' => true,
            'message' => 'Client deleted successfully',
            'data' => null
        ], 200);
    }
}
