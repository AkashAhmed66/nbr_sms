<?php

namespace Modules\API\App\Repositories;

use Illuminate\Http\Request;

use Modules\API\App\Http\Requests\CheckBalanceRequest;

interface IptspRepositoryInterface
{
  public function sendMessage(Request $request,$type);
  public function checkBalance(Request $request);
  public function checkUser($request);
  public function microseconds();
}
