<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use Illuminate\Support\Facades\{Lang,Log,Auth,Password};
use App\Models\{User};
use App\Http\Requests\Api\Auth\ForgetPasswordRequest;

class ForgotPasswordController extends Controller
{
    public function sendResetLinkEmail(ForgetPasswordRequest $request)
    {
        try{
            Log::info('ForgotPasswordController@sendResetLinkEmail: Sending reset password link');

            // Validate Request
            $request->validate(['email' => 'required|email']);

            // Send reset password link
            $status = \Password::sendResetLink(
                $request->only('email')
            );

            if ($status == Password::RESET_LINK_SENT) {
                return ResponseHelper::success(Lang::get('messages.reset_link_sent'), [], 200);
            }else{
                return ResponseHelper::error(Lang::get('messages.reset_link_failed'), [], 400);
            }

        }catch(\Exception $e){
            Log::error('ForgotPasswordController@sendResetLinkEmail: ' . $e->getMessage());
            return ResponseHelper::error($e->getMessage(), [], 500);
        }


    }
}
