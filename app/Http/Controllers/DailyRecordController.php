<?php

namespace App\Http\Controllers;

use App\Constants\AppConstant;
use App\Models\DailyRecord;
use App\Models\User;
use App\Models\UserRedis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use Exception;

class DailyRecordController extends Controller
{
    // Initialize user services.
    private $userCacheInstance;

    public function __construct()
    {
        $this->userCacheInstance = new UserRedis();
    }

    public function show(Request $request)
    {
        $today = $request->get('today');
        $latest = $request->get('latest');

        // Get only today date data.
        if ($today !== null) {
            $now = Carbon::now();
            $before = Carbon::now()->subDay();

            $data = DailyRecord::whereBetween('date', [$before, $now])->first();
            return response()->json($data);
        }
        // Get only latest date data.
        if ($latest !== null) {
            $data = DailyRecord::orderBy('date', 'desc')->first();
            return response()->json($data);
        }

        $data = DailyRecord::paginate(AppConstant::GET_BASE_LIMIT());
        return response()->json($data);
    }

    /**
     * Runner for calculate and store daily-basis report by interval of date.
     * Call the memoryCache database.
     * Store and call to data storage.
     */
    public function store()
    {
        // Interval of data fetched.
        $now = Carbon::now();
        $before = Carbon::now()->subDay();

        try {
            // Get total of data per 1 days.
            [$male, $female] = $this->userCacheInstance->get();

            // Begin database transaction.
            DB::beginTransaction();

            // Get 1 days data interval from users.
            // With index fields: created_at and gender.
            $avgAge = User::getAverageByGender($before, $now);

            // Insert into daily reports.
            $daily = DailyRecord::create([
                'id' => Uuid::uuid4(),
                'date' => Carbon::now(),
                'female_count' => $female,
                'male_count' => $male,
                'female_avg_age' => $avgAge[0]['average_age'], // Indicate female gender.
                'male_avg_age' => $avgAge[1]['average_age'], // Indicate male gender.
            ]);

            // Commit after transaction is done.            
            DB::commit();
            // Delete redis keys for that corresponding days.
            $this->userCacheInstance->clear();

            return response()->json([
                'success' => true,
                'data' => $daily,
                'message' => 'Successfully inserted.',
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
