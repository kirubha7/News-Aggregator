<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use Illuminate\Support\Facades\{Lang,Log,Auth,Password};
use App\Models\{User};
use App\Http\Requests\Api\Auth\{ResetPasswordRequest};

class ResetPasswordController extends Controller
{
    public function reset(ResetPasswordRequest $request)
    {
        try{
            Log::info('ResetPasswordController@reset: Resetting password');

            $user = User::where('email', $request->email)->first();
            $password = $request->password;

            // Reset password
            $status = \Password::reset(
                $request->only('email', 'token', 'password'),
                function ($user, $password) {
                    $user->forceFill([
                        'password' => bcrypt($password)
                    ])->save();
                }
            );

            return match ($status) {
                Password::PASSWORD_RESET => ResponseHelper::success(Lang::get('messages.password_reset_success')),
                Password::INVALID_USER => ResponseHelper::error(Lang::get('messages.invalid_user'), [], 400),
                Password::INVALID_TOKEN => ResponseHelper::error(Lang::get('messages.invalid_token'), [], 400),
                default => ResponseHelper::error(Lang::get('messages.password_reset_failed'), [], 400),
            };

        }catch(\Exception $e){
            Log::error('ResetPasswordController@reset: ' . $e->getMessage());
            return ResponseHelper::error($e->getMessage(), [], 500);
        }
    }
}
