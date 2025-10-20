<?php

namespace Modules\Transactions\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Users\App\Models\User;

class UserWallet extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   */
  protected $table = 'user_wallet';
  protected $fillable = [
                  'user_id',
                  'balance',
                  'balance_type'
              ];

  public function user()
  {
    return $this->belongsTo(User::class, 'user_id', 'id');
  }
}
