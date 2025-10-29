<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FetchGbartaDlrCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * Usage: php artisan gbarta:fetch-dlr
     */
    protected $signature = 'gbarta:fetch-dlr';

    /**
     * The console command description.
     */
    protected $description = 'Fetch DLR status from GBarta API for messages sent in the last 5 minutes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Fetching pending messages from sentmessages table...');

        // 1. Get messages with is_complete = null and created within last 5 mins
        $messages = DB::table('sms_records')
            ->where('status', 'Message Submitted')
            ->where('created_at', '>=', Carbon::now()->subMinutes(5))
            ->orderBy('id', 'desc')
            ->get();

        if ($messages->isEmpty()) {
            $this->info('No pending messages found.');
            return Command::SUCCESS;
        }

        foreach ($messages as $msg) {
            try {
                $this->info("Processing message ID: {$msg->id}");

                // 2. Call the GBarta API with orderid = messageid
                $apiUrl = "https://gbarta.gennet.com.bd/api/v1/send-sms-status";

                $response = Http::get($apiUrl, [
                    'message_id' => $msg->message_id, // use the messageid from sentmessages
                    'api_key' => env('GBARTA_SMS_SECRET_KEY'),
                ]);

                if ($response->failed()) {
                    $this->error("Failed to fetch DLR for message {$msg->orderid}");
                    $this->line("Response body: " . $response->body());
                    continue;
                }

                $data = $response->json();
                $status = $data['status'] ?? null;

                $this->info("data after api call: ". json_encode($data['data'][0]['status'] ?? []));
                if (!$status) {
                    $this->warn("No status returned for message {$msg->id}");
                    continue;
                }

                if($status === 'success' && isset($data['data'][0]['status']) && $data['data'][0]['status'] == "Delivered") {
                    // 3. Update the Outbox table based on reference_id
                    $affected = DB::table('outbox')
                        ->where('id', $msg->outbox_id) // use messageid as reference_id
                        ->update(['outbox.dlr_status' => $data['data'][0]['status']]);

                    DB::table('sms_records')
                        ->where('id', $msg->id)
                        ->update(['status' => $data['data'][0]['status']]);

                    if ($affected) {
                        $msg->status = $data['data'][0]['status'];
                        // $msg->save();
                        $this->info("DLR updated for message {$msg->id} with status: {$data['data'][0]['status']}");
                    } else {
                        $this->warn("No matching outbox record found for message {$msg->id}");
                    }
                }
            } catch (\Exception $e) {
                $this->error("Error processing message {$msg->id}: " . $e->getMessage());
            }
        }

        $this->info('DLR fetching completed.');
        return Command::SUCCESS;
    }
}
