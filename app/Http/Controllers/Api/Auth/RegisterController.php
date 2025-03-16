<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\StoreUserRequest;
use Illuminate\Http\Request;
use App\Models\User;
use App\Helpers\ResponseHelper;
use Illuminate\Support\Facades\{Lang, Log};

class RegisterController extends Controller
{
     /**
     * @OA\Post(
     *      path="/api/auth/register",
     *      operationId="registerUser",
     *      tags={"Authentication"},
     *      summary="Register a new user",
     *      description="Creates a new user account",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"name","email","password"},
     *              @OA\Property(property="name", type="string", example="John Doe"),
     *              @OA\Property(property="email", type="string", format="email", example="johndoe@example.com"),
     *              @OA\Property(property="password", type="string", format="password", example="secret123"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="User registered successfully",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="boolean", example=true),
     *              @OA\Property(property="message", type="string", example="User registered successfully"),
     *              @OA\Property(
     *                  property="data",
     *                  type="object",
     *                  @OA\Property(property="user", ref="#/components/schemas/User")
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Validation Error",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="boolean", example=false),
     *              @OA\Property(property="message", type="string", example="Validation failed"),
     *              @OA\Property(property="errors", type="object")
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
