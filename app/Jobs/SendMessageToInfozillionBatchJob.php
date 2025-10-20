<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Modules\Messages\App\Models\Message;
use Modules\Messages\App\Models\Outbox;
use App\Trait\MakingPayloadTrait;

class SendMessageToInfozillionBatchJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, MakingPayloadTrait;

    public $timeout = 180;
    public $tries = 3;

    protected $messageIds;

    public function __construct(array $messageIds)
    {
        $this->messageIds = $messageIds;
    }

    public function handle()
    {
        Log::info('into the Send Message To Infozillion Batch Job');
        Log::channel('sendMessageToInfozillionLog')->info('Into the Send Message To Infozillion Batch Job');
        Log::channel('sendMessageToInfozillionLog')->info("🚀 Concurrent batch started: " . count($this->messageIds));
        Log::channel('sendMessageToInfozillionLog')->debug("📋 Message IDs to process: " . implode(', ', $this->messageIds));

        $messages = Message::select('id', 'recipient', 'message', 'is_unicode', 'is_masking', 'senderID', 'status')
                ->whereIn('id', $this->messageIds)
                ->whereNull('scheduleDateTime')
                ->get();

        Log::channel('sendMessageToInfozillionLog')->info("📊 Found {$messages->count()} messages in database out of " . count($this->messageIds) . " requested");
        
        if ($messages->isEmpty()) {
            Log::channel('sendMessageToInfozillionLog')->warning("⚠️ No messages found to process");
            return;
        }

        
        // Prepare payloads
        Log::channel('sendMessageToInfozillionLog')->info("🔧 Starting payload preparation for {$messages->count()} messages");
        $requests = [];
        $maskingCount = 0;
        $nonMaskingCount = 0;
        
        foreach ($messages as $msg) {
            Log::channel('sendMessageToInfozillionLog')->debug("📝 Processing Message ID: {$msg->id} | Recipient: {$msg->recipient} | Masking: " . ($msg->is_masking ? 'Yes' : 'No'));

            $payload = $this->makeInfozilionPayload($msg);
            Log::channel('sendMessageToInfozillionLog')->debug("📦 Payload created for Message ID {$msg->id}: " . json_encode($payload));

            $apiUrl = env('AGGREGATOR_NON_MASKING_SMS_SEND_API_URL');
            if($msg->is_masking){
                $apiUrl = env('AGGREGATOR_SMS_SEND_API_URL');
                $maskingCount++;
                Log::channel('sendMessageToInfozillionLog')->debug("🎭 Message ID {$msg->id} using masking API: {$apiUrl}");
            } else {
                $apiUrl = env('AGGREGATOR_NON_MASKING_SMS_SEND_API_URL');
                $nonMaskingCount++;
                Log::channel('sendMessageToInfozillionLog')->debug("📱 Message ID {$msg->id} using non-masking API: {$apiUrl}");
            }

            Log::channel('sendMessageToInfozillionLog')->info("🛠️ Prepared payload for Message ID {$msg->id} | API URL: {$apiUrl}");

            $requests[$msg->id] = [
                'url' => $apiUrl,
                'payload' => $payload
            ];
        }
        
        Log::channel('sendMessageToInfozillionLog')->info("📈 Payload preparation complete | Masking: {$maskingCount} | Non-masking: {$nonMaskingCount}");

        // Execute concurrent API calls
        Log::channel('sendMessageToInfozillionLog')->info("🌐 Starting concurrent API calls for " . count($requests) . " requests");
        $startTime = microtime(true);
        
        $responses = Http::pool(function ($pool) use ($requests) {
            $promises = [];
            foreach ($requests as $id => $req) {
                Log::channel('sendMessageToInfozillionLog')->debug("🚀 Queuing API call for Message ID {$id} to URL: {$req['url']}");
                $promises[$id] = $pool->as($id)->post($req['url'], $req['payload']);
            }
            return $promises;
        });
        
        $endTime = microtime(true);
        $executionTime = round(($endTime - $startTime) * 1000, 2);
        Log::channel('sendMessageToInfozillionLog')->info("⏱️ Concurrent API calls completed in {$executionTime}ms");

        // Collect IDs processed successfully
        $successIds = [];
        $failedIds = [];

        // Handle responses
        Log::channel('sendMessageToInfozillionLog')->info("📥 Processing " . count($responses) . " API responses");
        
        foreach ($responses as $id => $response) {
            try {
                Log::channel('sendMessageToInfozillionLog')->debug("🔍 Processing response for Message ID: {$id}");
                
                $sendMessage = Message::find($id);
                if (!$sendMessage) {
                    Log::channel('sendMessageToInfozillionLog')->warning("⚠️ Message ID {$id} not found in database");
                    continue;
                }

                Log::channel('sendMessageToInfozillionLog')->debug("📊 Response status for Message ID {$id}: HTTP " . $response->status());

                if ($response->successful()) {
                    $responseData = $response->json();
                    Log::channel('sendMessageToInfozillionLog')->debug("📋 Raw response data for Message ID {$id}: " . json_encode($responseData));

                    // 🧾 Update Message table fields
                    $sendMessage->serverTxnId = $responseData['serverTxnId'] ?? null;
                    $sendMessage->serverResponseCode = $responseData['serverResponseCode'] ?? null;
                    $sendMessage->serverResponseMessage = $responseData['serverResponseMessage'] ?? null;
                    $sendMessage->a2pDeliveryStatus = $responseData['a2pDeliveryStatus'] ?? null;
                    $sendMessage->a2pSendSmsBusinessCode = $responseData['a2pSendSmsBusinessCode'] ?? null;
                    $sendMessage->deliveryStatus = $responseData['deliveryStatus'] ?? null;
                    $sendMessage->dndMsisdn = $responseData['dndMsisdn'] ?? null;
                    $sendMessage->invalidMsisdn = $responseData['invalidMsisdn'] ?? null;
                    $sendMessage->ansSendSmsHttpStatus = $responseData['ansSendSmsHttpStatus'] ?? null;
                    $sendMessage->ansSendSmsBusinessCode = $responseData['ansSendSmsBusinessCode'] ?? null;
                    $sendMessage->mnoResponseCode = $responseData['mnoResponseCode'] ?? null;
                    $sendMessage->mnoResponseMessage = $responseData['mnoResponseMessage'] ?? null;
                    $sendMessage->sent_at = now();
                    $sendMessage->status = 'Sent'; // set tentatively
                    
                    Log::channel('sendMessageToInfozillionLog')->debug("💾 Updating Message ID {$id} with status: Sent, TxnID: {$sendMessage->serverTxnId}");
                    $sendMessage->save();

                    $successIds[] = $sendMessage->id;

                    Log::channel('sendMessageToInfozillionLog')->info("✅ Message ID {$id} processed successfully | TxnID: {$sendMessage->serverTxnId} | Recipient: {$sendMessage->recipient}");
                } else {
                    $responseBody = $response->body();
                    Log::channel('sendMessageToInfozillionLog')->error("❌ Message ID {$id} failed | HTTP {$response->status()} | Response: {$responseBody}");
                    Log::channel('sendMessageToInfozillionLog')->debug("💾 Updating Message ID {$id} with status: Failed");
                    
                    $sendMessage->status = 'Failed';
                    $sendMessage->save();
                    $failedIds[] = $sendMessage->id;
                }
            } catch (\Throwable $e) {
                Log::channel('sendMessageToInfozillionLog')->error("💥 Exception for Message ID {$id}: " . $e->getMessage());
                Log::channel('sendMessageToInfozillionLog')->error("🔍 Exception trace: " . $e->getTraceAsString());
                Log::channel('sendMessageToInfozillionLog')->debug("💾 Updating Message ID {$id} with status: Failed due to exception");
                
                $failedIds[] = $id;
                Message::where('id', $id)->update(['status' => 'Failed']);
            }
        }

        Log::channel('sendMessageToInfozillionLog')->info("🏁 Batch processing completed");
        Log::channel('sendMessageToInfozillionLog')->info("📊 Final Statistics:");
        Log::channel('sendMessageToInfozillionLog')->info("   • Total requested: " . count($this->messageIds));
        Log::channel('sendMessageToInfozillionLog')->info("   • Found in DB: " . $messages->count());
        Log::channel('sendMessageToInfozillionLog')->info("   • Successfully processed: " . count($successIds));
        Log::channel('sendMessageToInfozillionLog')->info("   • Failed: " . count($failedIds));
        
        if (!empty($successIds)) {
            Log::channel('sendMessageToInfozillionLog')->debug("✅ Success IDs: " . implode(', ', $successIds));
        }
        
        if (!empty($failedIds)) {
            Log::channel('sendMessageToInfozillionLog')->debug("❌ Failed IDs: " . implode(', ', $failedIds));
        }
    }
}
