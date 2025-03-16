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
