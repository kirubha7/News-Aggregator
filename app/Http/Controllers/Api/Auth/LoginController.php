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
