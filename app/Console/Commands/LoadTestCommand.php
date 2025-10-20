<?php

namespace App\Console\Commands;

use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class LoadTestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run:load-test-command';

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
        for ($i = 0; $i < 1000000; $i++) {
            $payload = [
                "username"      => 'LoadTest',
                "password"      => '123456',
                "cli"           => '1000000000000',
                "msisdn"        => '8801734183130',
                "message"       => 'hello! this is topu from metronet',
                "clienttransid" => 'client' . microtime(true) . '_' . $i, // unique ID
                "rn_code"       => '71',
                "type"          => 'P',
                "longSMS"       => '',
                "isLongSMS"     => false,
                "dataCoding"    => '0',
                "isUnicode"     => false,
                "unicode"       => '',
                "isFlash"       => false,
                "flash"         => ''
            ];

            try {
                Log::channel('load_test')->info("Iteration {$i} | Payload: " . (new \DateTime())->format("Y-m-d H:i:s.v") . " " . json_encode($payload));
                
                $this->info("Iteration {$i} start " . (new \DateTime())->format("Y-m-d H:i:s.v"));

                $client = app(Client::class);
                $response = $client->post('https://116.193.222.194:8443/api/v2/promo/sendsms', [
                    'json' => $payload,
                ]);

                $this->info("Iteration {$i} complete " . (new \DateTime())->format("Y-m-d H:i:s.v"));

                $body = $response->getBody()->getContents();
                Log::channel('load_test')->info("Iteration {$i} | Response: " . (new \DateTime())->format("Y-m-d H:i:s.v") . " " . $body);


            } catch (\Exception $e) {
                // don't stop loop, just log
                Log::channel('load_test')->error("Iteration {$i} | Error: " . $e->getMessage(), [
                    'exception' => $e,
                    'payload'   => $payload,
                ]);

                $this->error("Iteration {$i} failed: " . $e->getMessage());
            }
        }
    }
}