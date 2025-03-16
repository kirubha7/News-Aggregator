<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Helpers\ResponseHelper;
use Illuminate\Support\Facades\{Lang,Log,Auth};
use App\Models\{User};


class LoginController extends Controller
{

        /**
     * User Login
     *
     * @OA\Post(
     *     path="/api/login",
     *     tags={"Authentication"},
     *     summary="User login",
     *     description="Logs in a user and returns an access token.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="email", example="kirubharaj777@gmail.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Login successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Login successful"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="user", type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="John Doe"),
     *                     @OA\Property(property="email", type="string", example="user@example.com"),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-03-16T12:00:00Z")
     *                 ),
     *                 @OA\Property(property="token", type="string", example="1|abcdef1234567890")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Invalid credentials"),
     *     @OA\Response(response=500, description="Internal Server Error")
     * )
     */

    public function login(LoginRequest $request)
    {
        try{
            Log::info('LoginController@login: Logging in user');

            // Validate Request
            $validatedData = $request->validated();

            if (!Auth::attempt($request->only('email', 'password'))) {
                return ResponseHelper::error('Invalid credentials', [], 401);
            }

            $user = Auth::user();

            $token = $user->createToken('auth_token')->plainTextToken;

            // Prepare response data
            $data['user'] = $user;
            $data['token'] = $token;

            // Return success response
            return ResponseHelper::success(Lang::get('messages.login_success'), $data, 201);
        }catch(\Exception $e){
            Log::error('LoginController@login: ' . $e->getMessage());

            // Return error response
            return ResponseHelper::error($e->getMessage(), [], 500);
        }
    }

    /**
 * @OA\Post(
 *     path="/api/logout",
 *     summary="Logout User",
 *     description="Logs out the authenticated user and revokes the token",
 *     operationId="logoutUser",
 *     tags={"Authentication"},
 *     security={{ "sanctum":{} }},
 *     @OA\Response(
 *         response=200,
 *         description="User logged out successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="User logged out successfully")
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized - Token missing or invalid",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Unauthorized")
 *         )
 *     )
 * )
 */

    public function logout(Request $request)
    {
        try{
            Log::info('LoginController@logout: Logging out user');

            // Revoke the token for current device
            $request->user()->currentAccessToken()->delete();

            //Revoke the token for all device
            //$request->user()->tokens()->delete();

            // Return success response
            return ResponseHelper::success(Lang::get('messages.user_logged_out'), [], 200);
        }catch(\Exception $e){
            Log::error('LoginController@logout: ' . $e->getMessage());

            // Return error response
            return ResponseHelper::error($e->getMessage(), [], 500);
        }
    }
}
