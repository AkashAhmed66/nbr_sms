<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;

class SMSTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sms:test';

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
        $url = "https://gbarta.gennet.com.bd/api/v1/smsapi";

        $params = [
            'api_key'  => '$2y$12$lYSwYl.I79LPpu4UaKBPVOlQw/.nP3J5CUBlI9MypwAHyd5aQKIVi',
            'type'     => 'text',
            'senderid' => '8809612345678',
            'msg'      => 'Test message sending at '.now()->format('Y-m-d H:i:s'),
            'numbers'  => '01629334432,01811562256,01818632643'
        ];

        try {
            $response = \Illuminate\Support\Facades\Http::get($url, $params);

            if ($response->successful()) {
                Log::channel('testsms')->info('Test SMS sent successfully from '. Request::root());
            } else {
                Log::channel('testsms')->info('Test SMS failed');
            }
        } catch (\Exception $e) {
            $this->error('Error calling API: ' . $e->getMessage());
        }
    }


}
