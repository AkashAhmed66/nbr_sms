<?php

namespace Modules\Messages\App\Models;

use Database\Factories\OutboxFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Smsconfig\App\Models\Operator;
use Modules\Smsconfig\App\Models\ServiceProvider;
use Modules\Users\App\Models\User;

class Outbox extends Model
{
  use HasFactory;

  protected $table = 'outbox';
  protected $guarded = [];

  protected static function newFactory()
  {
    return OutboxFactory::new();
  }


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
