<?php

namespace Modules\Transactions\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Users\App\Models\User;

class Transaction extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   */
  

  public function user()
  {
    return $this->belongsTo(User::class, 'user_id', 'id');
  }
}
