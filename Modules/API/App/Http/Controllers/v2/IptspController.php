<?php

namespace Modules\API\App\Http\Controllers\v2;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use  Modules\API\App\Repositories\IptspRepositoryInterface as IptspInterface;
use  Modules\API\App\Http\Requests\CheckBalanceRequest;

class IptspController extends Controller
{
  protected $iptspInterface;

  public function __construct(IptspInterface $iptspInterface)
  {
    $this->iptspInterface = $iptspInterface;
  }

  public function sendMessage(Request $request, $type)
  {

    return $this->iptspInterface->sendMessage($request,$type);
  }

  public function checkBalance(Request $request)
  {
    return $this->iptspInterface->checkBalance($request);
  }
}
