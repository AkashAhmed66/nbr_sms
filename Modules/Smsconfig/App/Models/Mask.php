<?php

namespace Modules\Smsconfig\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Users\App\Models\User;

class Mask extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   */
  protected $table = 'mask';
  protected $fillable = ['user_id', 'mask', 'status', 'created_by', 'created_at', 'updated_at'];

  public function user()
  {
    return $this->hasOne(User::class, 'id', 'user_id');
  }
}
