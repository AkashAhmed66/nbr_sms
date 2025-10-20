<?php

namespace App\Console\Commands;

use App\Jobs\SendMessageToInfozillionBatchJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Modules\Messages\App\Models\Message;
use App\Jobs\SendInfozillionBatchJob;

class SendInfozillionMessageCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'messages:dispatch-infozillion 
                            {--batch=1000 : Number of messages to fetch per batch} 
                            {--perJob=100 : Number of messages per job}';

    /**
     * The console command description.
     */
    protected $description = 'Dispatch messages to Infozillion via concurrent Horizon jobs';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $batchSize = (int) $this->option('batch');
        $perJob = (int) $this->option('perJob');

        Log::channel('sendMessageToInfozillionLog')->info("🚀 Starting Infozillion dispatcher | Batch={$batchSize}, perJob={$perJob}");

        while (true) {
            DB::beginTransaction();
            try {
                // Fetch messages that are not yet sent
                $messages = Message::select('id')
                    ->where('id', '293870')
                    ->where('status', '=', 'Queue')
                    ->whereNull('serverTxnId')
                    ->orderBy('id')
                    ->limit($batchSize)
                    ->lockForUpdate(true)
                    ->get();

                if ($messages->isEmpty()) {
                    DB::commit();
                    Log::channel('sendMessageToInfozillionLog')->info("✅ No pending messages found. Sleeping 100ms...");
                    usleep(100000); // sleeps for 100 milliseconds (100,000 microseconds)
                    continue;
                }


                //// Mark as sending before commit
                $messages->each(function ($message) {
                    $message->status = 'Sending';
                    $message->save();
                });


                $chunks = $messages->chunk($perJob);

                foreach ($chunks as $chunk) {
                    $messageIds = $chunk->pluck('id')->toArray();
                    dispatch(new SendMessageToInfozillionBatchJob($messageIds))
                        ->onQueue('sendMessageToInfozillion');
                }

                Log::channel('sendMessageToInfozillionLog')->info("📦 Dispatched " . count($chunks) . " jobs (" . $messages->count() . " messages)");

                DB::commit();
            } catch (\Throwable $e) {
                DB::rollBack();
                Log::channel('sendMessageToInfozillionLog')->error("❌ Dispatcher error: " . $e->getMessage());
            }

            // Avoid CPU overuse
            usleep(100000); // sleeps for 100 milliseconds (100,000 microseconds)
        }

        return Command::SUCCESS;
    }
}
