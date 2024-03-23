<?php

namespace App\Events;

use App\Models\DailyRecord;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DailyRecordOnChange
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $dailyRecord;

    /**
     * Create a new event instance.
     */
    public function __construct(DailyRecord $dailyRecord)
    {
        $this->dailyRecord = $dailyRecord;
    }
}
