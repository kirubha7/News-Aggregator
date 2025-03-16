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

    /**
 * @OA\Post(
 *     path="/api/password/email",
 *     summary="Send Reset Password Link",
 *     description="Sends a password reset link to the user's email.",
 *     operationId="sendResetLinkEmail",
 *     tags={"Authentication"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"email"},
 *             @OA\Property(property="email", type="string", format="email", example="kirubharaj777@gmail.com")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Reset password link sent successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Reset password link sent successfully")
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid email or unable to send reset link",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Unable to send reset link")
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

    public function sendResetLinkEmail(ForgetPasswordRequest $request)
    {
        try{
            Log::info('ForgotPasswordController@sendResetLinkEmail: Sending reset password link');

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
