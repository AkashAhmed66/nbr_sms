<?php

namespace App\Console\Commands;

use App\Jobs\CheckMessageDeliveryStatusJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Modules\Messages\App\Models\Message;

class AggregatorDLRStatusUpdateFromInfozillionCommand extends Command
{
    protected $signature = 'get:aggregator-dlr-status-update-from-infozillion 
                            {--batch=1000 : Number of messages to fetch from DB at once} 
                            {--perJob=100 : Number of messages per job}
                            {--interval=20 : Sleep time (in seconds) between loops}';

    protected $description = 'Continuously dispatch jobs to update Aggregator DLR Status from Infozillion';

    public function handle(): int
    {
        $batchSize = (int) $this->option('batch') ?: 1000;
        $perJob    = (int) $this->option('perJob') ?: 100;
        $interval  = (int) $this->option('interval') ?: 20;

        Log::channel('getDLRLog')->info("Starting Aggregator DLR command loop. Batch={$batchSize}, perJob={$perJob}, Interval={$interval}s");

        while (true) {
            try {
                $processed = $this->processMessagesBatch($batchSize, $perJob);

                if ($processed === 0) {
                    Log::channel('getDLRLog')->info("No messages to process. Sleeping for {$interval} seconds...");
                } else {
                    Log::channel('getDLRLog')->info("Processed {$processed} messages. Sleeping for {$interval} seconds...");
                }

                sleep($interval);
            } catch (\Throwable $e) {
                Log::channel('getDLRLog')->error("Exception occurred: " . $e->getMessage(), [
                    'trace' => $e->getTraceAsString(),
                ]);
                sleep($interval);
            }
        }

        return Command::SUCCESS; // Technically unreachable
    }

    private function processMessagesBatch(int $batchSize, int $perJob): int
    {
        $totalProcessed = 0;

        Message::with("outboxMessage")
            ->where("aggregator_dlr_status_updated_from_infozilion", 0)
            ->where('created_at', '<=', now()->subSeconds(20))
            ->whereNotNull("serverTxnId")
            ->orderBy("id")
            ->chunkById($batchSize, function ($messages) use ($perJob, &$totalProcessed) {

                $chunks = $messages->chunk($perJob);

                foreach ($chunks as $group) {
                    $payload = [];

                    foreach ($group as $message) {
                        $numbers = $message->outboxMessage
                            ->pluck('destmn')
                            ->map(fn($n) => $this->normalizeMsisdn($n))
                            ->toArray();

                        $payload[] = [
                            'id'         => $message->id,
                            'orderId'    => $message->serverTxnId,
                            'cli'        => $message->outboxMessage->first()->mask ?? $message->senderID,
                            'msisdnList' => $numbers,
                        ];

                        $message->update(['aggregator_dlr_status_updated_from_infozilion' => 1]);
                        $totalProcessed++;
                    }

                    CheckMessageDeliveryStatusJob::dispatch($payload)->onQueue('aggregator-dlr-update');

                    Log::channel('getDLRLog')->info("Dispatched job with " . count($payload) . " messages.");
                }
            });

        return $totalProcessed;
    }

    private function normalizeMsisdn(string $number): string
    {
        $number = preg_replace('/\D+/', '', $number);

        if (str_starts_with($number, '+88')) {
            $number = substr($number, 1);
        } elseif (!str_starts_with($number, '88')) {
            $number = '88' . $number;
        }

        return $number;
    }
}
