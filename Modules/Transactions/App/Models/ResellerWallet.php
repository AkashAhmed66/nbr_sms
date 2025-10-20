<?php

namespace Modules\Transactions\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Users\App\Models\User;

class ResellerWallet extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   */
  protected $table = 'reseller_wallet';
  protected $fillable = [
                  'user_id',
                  'available_balance', 
                  'non_masking_balance',
                  'masking_balance',
                  'email_balance',
              ];

  public function user()
  {
    return $this->belongsTo(User::class, 'user_id', 'id');
  }
}
