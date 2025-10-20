<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;
use Modules\Users\App\Models\User;

class UpdateUserRedisBalance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-user-redis-balance';

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
        if (env('APP_TYPE') != 'Aggregator') {

            $users = User::all();

            foreach ($users as $user) {
                $key = "user:{$user->username}";
                $json = Redis::get($key);
                $userData = $json ? json_decode($json, true) : [];
                $userData['available_balance'] = $user->available_balance;
                Redis::set($key, json_encode($userData));
            }
        }
    }
}
