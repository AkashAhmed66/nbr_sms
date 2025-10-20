<?php

namespace Modules\API\App\Http\Controllers\v2;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use  Modules\API\App\Repositories\LoadTestRepositoryInterface as LoadTestInterface;
use  Modules\API\App\Http\Requests\CheckBalanceRequest;

class LoadTestController extends Controller
{
  protected $loadTestInterface;

  public function __construct(LoadTestInterface $loadTestInterface)
  {
    $this->loadTestInterface = $loadTestInterface;
  }

  public function sendMessage(Request $request, $type)
  {

    return $this->loadTestInterface->sendMessage($request,$type);
  }

  public function checkBalance(Request $request)
  {
    return $this->loadTestInterface->checkBalance($request);
  }
}
