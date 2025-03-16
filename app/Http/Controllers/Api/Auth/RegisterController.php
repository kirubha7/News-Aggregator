<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\StoreUserRequest;
use Illuminate\Http\Request;
use App\Models\User;
use App\Helpers\ResponseHelper;
use Illuminate\Support\Facades\{Lang, Log};
use OpenApi\Annotations as OA;

/**
 * @OA\OpenApi(
 *     @OA\Info(
 *         title="News Aggregator API",
 *         version="1.0.0",
 *         description="API documentation for the News Aggregator",
 *         @OA\Contact(
 *             email="support@example.com"
 *         ),
 *         @OA\License(
 *             name="Apache 2.0",
 *             url="http://www.apache.org/licenses/LICENSE-2.0.html"
 *         )
 *     ),
 *     @OA\Components(
 *         @OA\SecurityScheme(
 *             securityScheme="sanctum",
 *             type="http",
 *             scheme="bearer",
 *             bearerFormat="JWT"
 *         )
 *     )
 * )
 */


class RegisterController extends Controller
{
      /**
     * Register a new user.
     *
     * @OA\Post(
     *     path="/api/register",
     *     tags={"Authentication"},
     *     summary="Register a new user",
     *     description="Creates a new user and returns user details.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email","password"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="johndoe@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="User created successfully"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="user", type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="John Doe"),
     *                     @OA\Property(property="email", type="string", example="johndoe@example.com"),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-03-16T12:00:00Z")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=500, description="Internal Server Error")
     * )
     */

    public function register(StoreUserRequest $request)
    {
        try {

            Log::info('RegisterController@register: Registering user');

            // Validate Request
            $validatedData = $request->validated();

            // Create the user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);

            // Prepare response data
            $data['user'] = $user;

            Log::info('RegisterController@register: User created successfully');

            // Return success response
            return ResponseHelper::success(Lang::get('messages.user_created'), $data, 201);

        } catch (\Exception $e) {
            Log::error('RegisterController@register: ' . $e->getMessage());

            // Return error response
            return ResponseHelper::error($e->getMessage(), [], 500);
        }
    }

}
