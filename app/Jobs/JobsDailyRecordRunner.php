<?php

namespace App\Jobs;

use App\Http\Controllers\DailyRecordController;
use App\Models\DailyRecord;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class JobsDailyRecordRunner implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    private $runner;

    public function __construct()
    {
        $this->runner = new DailyRecordController(); // Invoke.
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->runner->store();
    }
}
