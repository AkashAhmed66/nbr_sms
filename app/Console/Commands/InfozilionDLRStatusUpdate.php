<?php

namespace App\Console\Commands;

use App\Jobs\InfozilionDLRStatusUpdateJob;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Modules\Messages\App\Models\Outbox;
use Symfony\Component\Console\Output\Output;

class InfozilionDLRStatusUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:infozilion-dlr-status-update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $outbox = Outbox::where("infozillion_dlr_status_sent", 0)
        //->where("destmn","=", "8801628351700")
        ->orderByDesc("id")->limit(500)
        ->get();

        if ($outbox->isEmpty()) {
            Log::channel('infozilionStatusUpdate')->info("No messages found for Infozillion DLR status update.");
        } else {
            InfozilionDLRStatusUpdateJob::dispatch($outbox);
        }
    }

}
