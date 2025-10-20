<?php

namespace App\Console\Commands;

use App\Services\RetryRequestService;
use Illuminate\Console\Command;

class RetryRequestCommand extends Command
{
  protected $signature = 'retry:process';
  protected $description = 'Process retry requests every 10 minutes';

  protected $retryRequestService;

  public function __construct(RetryRequestService $retryRequestService)
  {
    parent::__construct();
    $this->retryRequestService = $retryRequestService;
  }

  public function handle()
  {
    //$this->retryRequestService->handleRetryRequest();
  }
}
