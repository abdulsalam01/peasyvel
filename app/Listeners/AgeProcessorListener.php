<?php

namespace App\Listeners;

use App\Events\DailyRecordOnChange;
use App\Models\User;
use Carbon\Carbon;

class AgeProcessorListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(DailyRecordOnChange $event): void
    {
        // Get master single record data. 
        $dailyRecord = $event->dailyRecord;
        // Between ranges of days.
        $now = Carbon::createFromDate($dailyRecord->getOriginal('date'));
        $before = Carbon::createFromDate($dailyRecord->getOriginal('date'))->subDay();

        // Query the data based on corresponding datetime.
        $avgAge = User::getAverageByGender($before, $now);
        if ($dailyRecord->isDirty('female_count')) {
            $dailyRecord->female_avg_age = $avgAge[0]['average_age'];
        }
        if ($dailyRecord->isDirty('male_count')) {
            $dailyRecord->male_avg_age = $avgAge[1]['average_age'];
        }

        $dailyRecord->save();
    }
}
