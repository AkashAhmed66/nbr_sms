<?php

namespace Modules\API\App\Repositories;

use Illuminate\Http\Request;

interface LoadTestRepositoryInterface
{
  public function sendMessage(Request $request,$type);
  public function checkBalance(Request $request);
  public function checkUser($request);
  public function microseconds();
}
