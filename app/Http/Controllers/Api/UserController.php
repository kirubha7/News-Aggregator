<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Log,Auth,Lang};
use App\Helpers\ResponseHelper;
use App\Http\Requests\Api\User\UserPreferenceRequest;
use App\Models\{Article,Category,Source,UserPreferredAuthor};
use App\Repositories\Contracts\PreDefinedDataRepositoryInterface;

class UserController extends Controller
{
    protected $PreDefinedDataRepository;

    public function __construct(PreDefinedDataRepositoryInterface $PreDefinedDataRepository)
    {
        $this->PreDefinedDataRepository = $PreDefinedDataRepository;
    }


    /**
 * @OA\Get(
 *     path="/api/user",
 *     summary="Get Authenticated User Details",
 *     description="Fetches the details of the authenticated user.",
 *     operationId="getUserDetails",
 *     tags={"User"},
 *     security={{ "sanctum":{} }},
 *     @OA\Response(
 *         response=200,
 *         description="User details retrieved successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="User details fetched successfully"),
 *             @OA\Property(property="data", type="object",
 *                 @OA\Property(property="user", type="object",
 *                     @OA\Property(property="id", type="integer", example=1),
 *                     @OA\Property(property="name", type="string", example="John Doe"),
 *                     @OA\Property(property="email", type="string", example="johndoe@example.com"),
 *                     @OA\Property(property="created_at", type="string", format="date-time", example="2024-03-16T12:00:00Z")
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized - Token missing or invalid",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Unauthorized")
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal server error",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Internal Server Error")
 *         )
 *     )
 * )
 */


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


    /**
 * @OA\Post(
 *     path="/api/user/preferences",
 *     summary="Update User Preferences",
 *     description="Updates the user preferences including authors, categories, and sources.",
 *     operationId="updateUserPreferences",
 *     tags={"User"},
 *     security={{ "sanctum":{} }},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="authors", type="array",
 *                 @OA\Items(type="string", example="John Doe")
 *             ),
 *             @OA\Property(property="category", type="array",
 *                 @OA\Items(type="integer", example=1)
 *             ),
 *             @OA\Property(property="sources", type="array",
 *                 @OA\Items(type="integer", example=2)
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User preferences updated successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="User preferences updated successfully"),
 *             @OA\Property(property="data", type="object",
 *                 @OA\Property(property="user", type="object",
 *                     @OA\Property(property="id", type="integer", example=1),
 *                     @OA\Property(property="name", type="string", example="John Doe"),
 *                     @OA\Property(property="email", type="string", example="johndoe@example.com")
 *                 ),
 *                 @OA\Property(property="preferences", type="object",
 *                     @OA\Property(property="authors", type="array",
 *                         @OA\Items(type="string")
 *                     ),
 *                     @OA\Property(property="categories", type="array",
 *                         @OA\Items(
 *                             @OA\Property(property="id", type="integer", example=1),
 *                             @OA\Property(property="name", type="string", example="Technology")
 *                         )
 *                     ),
 *                     @OA\Property(property="sources", type="array",
 *                         @OA\Items(
 *                             @OA\Property(property="id", type="integer", example=2),
 *                             @OA\Property(property="name", type="string", example="BBC News")
 *                         )
 *                     )
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Bad request - Invalid input data",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Invalid input data")
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized - Token missing or invalid",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Unauthorized")
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal server error",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Internal Server Error")
 *         )
 *     )
 * )
 */

    public function updatePreferences(UserPreferenceRequest $request){
        try{
            Log::info('UserController@updatePreferences: Updating User Preferences');

            $user = auth()->user();
            $preferences = $user->preferences()->firstOrCreate(['user_id' => $user->id]);

            $validated = $request->validated();

            if (!empty($validated['authors'])) {
                UserPreferredAuthor::where('user_id', $user->id)->delete();

                $insertData = collect($validated['authors'])->map(fn ($author) => [
                    'user_id' => $user->id,
                    'author' => $author
                ])->toArray();

                UserPreferredAuthor::upsert(
                    $insertData,
                    ['user_id', 'author'],
                );
            }

            // Sync the relationships
            $preferences->categories()->sync($validated['category'] ?? []);
            $preferences->sources()->sync($validated['sources'] ?? []);

            // Reload preferences with relationships
            $preferences->load(['authors', 'categories:id,name', 'sources:id,name']);

            $data['user'] = $user;
            $data['preferences'] = $preferences;

            return ResponseHelper::success(Lang::get('messages.update_preferences'), $data, 200);
        }catch(\Exception $e){
            Log::error('UserController@updatePreferences: ' . $e->getMessage());

            // Return error response
            return ResponseHelper::error($e->getMessage(), [], 500);
        }
    }

    /**
 * @OA\Get(
 *     path="/api/authors",
 *     summary="Get Authors List",
 *     description="Fetches a list of unique authors from articles.",
 *     operationId="getAuthors",
 *     tags={"PreDefined Data"},
 *     @OA\Response(
 *         response=200,
 *         description="Authors retrieved successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Authors fetched successfully"),
 *             @OA\Property(property="data", type="array",
 *                 @OA\Items(type="string", example="John Doe")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal server error",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Internal Server Error")
 *         )
 *     )
 * )
 */


    public function authors(){
        try{
            Log::info('UserController@authors: Fetching authors list');

            // Prepare response data
            $data['authors'] = $this->PreDefinedDataRepository->getAuthors();

            // Return success response
            return ResponseHelper::success(Lang::get('messages.authors'), $data, 200);
        }catch(\Exception $e){
            Log::error('UserController@authors: ' . $e->getMessage());

            // Return error response
            return ResponseHelper::error($e->getMessage(), [], 500);
        }
    }

    /**
 * @OA\Get(
 *     path="/api/category",
 *     summary="Get Categories List",
 *     description="Fetches a list of available categories.",
 *     operationId="getCategories",
 *     tags={"PreDefined Data"},
 *     @OA\Response(
 *         response=200,
 *         description="Categories retrieved successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Categories fetched successfully"),
 *             @OA\Property(property="data", type="array",
 *                 @OA\Items(
 *                     @OA\Property(property="id", type="integer", example=1),
 *                     @OA\Property(property="name", type="string", example="Technology")
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal server error",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Internal Server Error")
 *         )
 *     )
 * )
 */

    public function category(){
        try{
            Log::info('UserController@category: Fetching category list');

             // Prepare response data
             $data['category'] = $this->PreDefinedDataRepository->getCategories();

             // Return success response
             return ResponseHelper::success(Lang::get('messages.category'), $data, 200);
        }catch(\Exception $e){
            Log::error('UserController@category: ' . $e->getMessage());

            // Return error response
            return ResponseHelper::error($e->getMessage(), [], 500);
        }
    }

    /**
 * @OA\Get(
 *     path="/api/sources",
 *     summary="Get Sources List",
 *     description="Fetches a list of available news sources.",
 *     operationId="getSources",
 *     tags={"PreDefined Data"},
 *     @OA\Response(
 *         response=200,
 *         description="Sources retrieved successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Sources fetched successfully"),
 *             @OA\Property(property="data", type="array",
 *                 @OA\Items(
 *                     @OA\Property(property="id", type="integer", example=1),
 *                     @OA\Property(property="name", type="string", example="BBC News")
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal server error",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Internal Server Error")
 *         )
 *     )
 * )
 */

    public function sources(){
        try{
            Log::info('UserController@sources: Fetching sources list');

            // Prepare response data
            $data['sources'] = $this->PreDefinedDataRepository->getSources();

            // Return success response
            return ResponseHelper::success(Lang::get('messages.source'), $data, 200);
        }catch(\Exception $e){
            Log::error('UserController@sources: ' . $e->getMessage());

            // Return error response
            return ResponseHelper::error($e->getMessage(), [], 500);
        }
    }
}
