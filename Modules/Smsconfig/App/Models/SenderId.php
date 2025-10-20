<?php

namespace Modules\Smsconfig\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Smsconfig\Database\factories\SenderIdFactory;
use Modules\Users\App\Models\User;

class SenderId extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   */
  protected $table = 'senderid';
  protected $fillable = ['user_id', 'count', 'senderID', 'status', 'assigned_user_id'];

  public function reseller()
  {
//        return $this->belongsTo(Reseller::class, 'reseller_id', 'id');
    return $this->hasOne(User::class, 'id', 'user_id');
  }

  public function user()
  {
    return $this->hasOne(User::class, 'id', 'assigned_user_id');
  }
}
