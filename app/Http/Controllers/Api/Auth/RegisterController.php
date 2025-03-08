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
