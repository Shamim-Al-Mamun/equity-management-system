<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Spatie\Activitylog\Models\Activity;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Audit Trail",
 *     description="Audit trail for user actions"
 * )
 */
class AuditController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/audit-logs",
     *     summary="Get audit trail logs",
     *     tags={"Audit Trail"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of audit logs",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="current_page", type="integer", example=1),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="log_name", type="string", example="client"),
     *                     @OA\Property(property="description", type="string", example="Client record has been updated"),
     *                     @OA\Property(property="subject_type", type="string", example="App\\Models\\Client"),
     *                     @OA\Property(property="subject_id", type="integer", example=5),
     *                     @OA\Property(property="causer_type", type="string", example="App\\Models\\User"),
     *                     @OA\Property(property="causer_id", type="integer", example=2),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-01T00:00:00Z")
     *                 )
     *             ),
     *             @OA\Property(property="last_page", type="integer", example=3),
     *             @OA\Property(property="total", type="integer", example=60)
     *         )
     *     )
     * )
     */
    public function index()
    {
        $logs = Activity::with('causer')->latest()->paginate(20);
    
        return response()->json([
            'status' => true,
            'message' => 'Activity logs retrieved successfully',
            'data' => $logs
        ], 200);
    }
}
