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
    /**
 * @OA\Post(
 *     path="/api/password/reset",
 *     summary="Reset Password",
 *     description="Resets the user's password using a valid token.",
 *     operationId="resetPassword",
 *     tags={"Authentication"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"email", "token", "password"},
 *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
 *             @OA\Property(property="token", type="string", example="sample-reset-token"),
 *             @OA\Property(property="password", type="string", format="password", example="newSecurePassword")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Password reset successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Password reset successfully")
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid token or user",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Invalid token or user")
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Server error",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Internal Server Error")
 *         )
 *     )
 * )
 */

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
