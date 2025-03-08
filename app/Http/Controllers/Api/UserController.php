<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Log,Auth,Lang};
use App\Helpers\ResponseHelper;

class UserController extends Controller
{
    public function userDetails(Request $request){
        try{
            Log::info('UserController@userDetails: Fetching user details');

            // Get authenticated user
            $user = Auth::user();

            // Prepare response data
            $data['user'] = $user;

            // Return success response
            return ResponseHelper::success(Lang::get('messages.user_details'), $data, 200);
        }catch(\Exception $e){
            Log::error('UserController@userDetails: ' . $e->getMessage());

            // Return error response
            return ResponseHelper::error($e->getMessage(), [], 500);
        }
    }
}
