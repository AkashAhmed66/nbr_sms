<?php

namespace Modules\Messages\App\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Smsconfig\App\Models\Operator;
use Modules\Smsconfig\App\Models\ServiceProvider;
use Modules\Users\App\Models\User;

class OutboxHistory extends Model
{
    
  protected $table = 'outbox_history';

  protected $guarded = [];


  public function user()
  {
    return $this->belongsTo(User::class, 'user_id', 'id');
  }

  public function channel()
  {
    return $this->belongsTo(ServiceProvider::class, 'channel_id', 'id');
  }

  public function sendMessage()
  {
    return $this->belongsTo(Message::class, 'reference_id', 'id');
  }

  public function operator()
  {
    return $this->hasOne(Operator::class, 'prefix', 'operator_prefix');
  }
}
