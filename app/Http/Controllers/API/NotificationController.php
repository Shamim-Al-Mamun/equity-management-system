<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use App\Notifications\GreetingNotification;
use OpenApi\Annotations as OA;

class NotificationController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/send-greeting-emails",
     *     tags={"Notifications"},
     *     summary="Trigger greeting emails to all users",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Greeting emails sent successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Greeting emails sent successfully.")
     *         )
     *     )
     * )
     */
    public function sendGreetingEmails()
    {
        $users = User::all();
        Notification::send($users, new GreetingNotification());

        return response()->json(['message' => 'Greeting emails sent successfully.']);
    }
}
