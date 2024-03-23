<?php

namespace App\Http\Controllers;

use App\Constants\AppConstant;
use App\Events\DailyRecordOnChange;
use App\Models\DailyRecord;
use App\Models\User;
use App\Models\UserRedis;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use Exception;
use Illuminate\Http\Request;

class ViewController extends Controller
{

    // Initialize user services.
    private $userCacheInstance;

    public function __construct()
    {
        $this->userCacheInstance = new UserRedis();
    }

    public function viewHome()
    {
        return view('pages.welcome');
    }

    public function viewUser()
    {
        $data = User::paginate(AppConstant::GET_BASE_LIMIT());
        return view('pages.users.index')->with('data', $data);
    }

    public function viewReport()
    {
        return view('pages.reports.index');
    }

    public function synchronize($id)
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

            if ($user->gender === AppConstant::MALE) {
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
            return $this->viewUser();
        } catch (Exception $e) {
            DB::rollBack();

            // Return failure response.
            return $this->viewUser();
        }
    }
}
