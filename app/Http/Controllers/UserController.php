<?php

namespace App\Http\Controllers;

use App\Constants\AppConstant;
use App\Events\DailyRecordOnChange;
use App\Http\Services\UserService;
use App\Models\DailyRecord;
use App\Models\User;
use App\Models\UserRedis;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use Exception;

class UserController extends Controller
{
    // Initialize user services.
    private $apiInstance;
    private $userCacheInstance;

    public function __construct()
    {
        $this->apiInstance = new UserService();
        $this->userCacheInstance = new UserRedis();
    }

    public function show()
    {
        $data = User::paginate(AppConstant::GET_BASE_LIMIT());
        return response()->json($data);
    }

    /**
     * API for delete and syncronize data.
     */
    public function delete($id)
    {
        try {
            // Begin database transaction.            
            DB::beginTransaction();
            // Counter for male and female.
            $counter = [
                AppConstant::MALE => 0,
                AppConstant::FEMALE => 0,
            ];
            
            // Find user by ID.
            $user = User::find($id);
            $before = Carbon::createFromDate($user->created_at);
            $now = Carbon::createFromDate($user->created_at)->addDay();

            // Find corresponding daily jobs.
            $dailyRecord = DailyRecord::whereBetween('date', [$before, $now])->first();
            
            if($user->gender === AppConstant::MALE) {
                $dailyRecord->male_count -= 1;
                $counter[AppConstant::MALE] = -1;
            } else {
                $dailyRecord->female_count -= 1;
                $counter[AppConstant::FEMALE] = -1;                
            }


            // Invoke the listener caller.
            DailyRecordOnChange::dispatch($dailyRecord);

            $user->delete();

            // Commit after transaction is done.
            DB::commit();
            // Delete corresponding data by one on redis to make data consistent.
            $this->userCacheInstance->store($counter);

            // Return success response.
            return response()->json([
                'success' => true,
                'data' => $user,
                'message' => 'Successfully sync data',
            ]);
        } catch (Exception $e) {
            DB::rollBack();

            // Return failure response.
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Runner for get user data by interval of time.
     * Call the external api.
     * Call the memoryCache database.
     * Store and call to data storage.
     */
    public function store()
    {
        // Safe to call directly, since error handling already covered.
        // Instance for call external-api.
        $sources = $this->apiInstance->invoke();

        try {
            // Begin database transaction.
            DB::beginTransaction();
            // Counter for male and female.
            $counter = [
                AppConstant::MALE => 0,
                AppConstant::FEMALE => 0,
            ];

            // Interate over fetched api.
            foreach ($sources as $source) {
                // Extract data.
                $id = $source['login']['uuid'];
                $gender = $source['gender'];
                $age = $source['dob']['age'];
                $name = $source['name'];
                $location = $source['location'];

                // Do upsert.
                $affected = User::updateOrCreate(
                    [
                        'id' => $id,
                    ],
                    [
                        'gender' => $gender,
                        'age' => $age,
                        'name' => json_encode($name),
                        'location' => json_encode($location),
                    ]
                );

                // Check if the upsert operation affected any rows.
                if ($affected === null) {
                    throw new Exception("Upsert failed for user with ID $id");
                }
                // Existing data, then skip the calculation.
                if ($affected->wasRecentlyCreated === false) {
                    continue;
                }

                // Counter data.
                $gender === AppConstant::MALE ? $counter[AppConstant::MALE]++ : $counter[AppConstant::FEMALE]++;
            }

            // Commit after transaction is done.
            DB::commit();
            // Store on redis after committed.
            $this->userCacheInstance->store($counter);

            // Return success response.
            return response()->json([
                'success' => true,
                'data' => $counter,
                'message' => 'Successfully fetched and inserted.',
            ]);
        } catch (Exception $e) {
            DB::rollBack();

            // Return failure response.
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => $e->getMessage(),
            ]);
        }
    }
}
