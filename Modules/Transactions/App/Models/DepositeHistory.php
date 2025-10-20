<?php

namespace Modules\Transactions\App\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Users\App\Models\Reseller;
use Modules\Users\App\Models\User;

class DepositeHistory extends Model
{
  protected $table = 'deposit_history';

  public function user()
  {
    return $this->belongsTo(User::class, 'user_id', 'id');
  }

  public function depositBy()
  {
    return $this->belongsTo(User::class, 'deposit_by', 'id');
  }

  public function reseller()
  {
    return $this->belongsTo(Reseller::class, 'reseller_id', 'id');
  }
}
