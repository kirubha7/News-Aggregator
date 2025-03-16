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
     * @OA\Post(
     *      path="/api/auth/login",
     *      operationId="loginUser",
     *      tags={"Authentication"},
     *      summary="User Login",
     *      description="Authenticates a user and returns an access token",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"email","password"},
     *              @OA\Property(property="email", type="string", format="email", example="johndoe@example.com"),
     *              @OA\Property(property="password", type="string", format="password", example="secret123")
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="User logged in successfully",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="boolean", example=true),
     *              @OA\Property(property="message", type="string", example="Login successful"),
     *              @OA\Property(
     *                  property="data",
     *                  type="object",
     *                  @OA\Property(property="user", ref="#/components/schemas/User"),
     *                  @OA\Property(property="token", type="string", example="1|abcdefghij1234567890")
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Invalid credentials",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="boolean", example=false),
     *              @OA\Property(property="message", type="string", example="Invalid credentials")
     *          )
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Server Error",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="boolean", example=false),
     *              @OA\Property(property="message", type="string", example="Internal Server Error")
     *          )
     *      )
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
     *      path="/api/auth/logout",
     *      operationId="logoutUser",
     *      tags={"Authentication"},
     *      summary="User Logout",
     *      description="Logs out the authenticated user and revokes the access token",
     *      security={{ "bearerAuth": {} }},
     *      @OA\Response(
     *          response=200,
     *          description="User logged out successfully",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="boolean", example=true),
     *              @OA\Property(property="message", type="string", example="User logged out successfully")
     *          )
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Server Error",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="boolean", example=false),
     *              @OA\Property(property="message", type="string", example="Internal Server Error")
     *          )
     *      )
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
