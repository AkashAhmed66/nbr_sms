<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Modules\Smsconfig\App\Models\Rate;
use Modules\Transactions\App\Models\UserWallet;
use Modules\Users\App\Models\Reseller;

class User extends Authenticatable
{
  use HasFactory, Notifiable;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'name',
    'email',
    'password',
  ];

  /**
   * The attributes that should be hidden for serialization.
   *
   * @var array<int, string>
   */
  protected $hidden = [
    'password',
    'remember_token',
  ];

  /**
   * Get the attributes that should be cast.
   *
   * @return array<string, string>
   */
  protected function casts(): array
  {
    return [
      'email_verified_at' => 'datetime',
      'password' => 'hashed',
    ];
  }

  public function reseller()
  {
    return $this->belongsTo(Reseller::class, 'reseller_id', 'id');
  }

  public function createBy()
  {
    return $this->belongsTo(\Modules\Users\App\Models\User::class, 'created_by', 'id');
  }

  public function creator()
  {
    return $this->hasOne(User::class, 'id', 'created_by');
  }

  public function smsRate()
  {
    return $this->belongsTo(Rate::class, 'sms_rate_id', 'id');
  }

  public function emailRate()
  {
    return $this->belongsTo(Rate::class, 'email_rate_id', 'id');
  }

  public function wallet()
  {
    return $this->hasOne(UserWallet::class, 'user_id', 'id');
  }

  public function getIsAdminAttribute()
  {
    return in_array($this->id_user_group, [1, 2]);
  }

  public function getIsResellerAttribute()
  {
    return $this->id_user_group == 3;
  }

  public function getIsUserAttribute()
  {
    return $this->id_user_group > 3;
  }

  public function isSuperAdmin()
  {
    return $this->id_user_group == 1;
  }

  public function isAdmin()
  {
    return $this->id_user_group == 2;
  }

  public function isReseller()
  {
    return $this->id_user_group == 3;
  }

  public function isCustomer()
  {
    return $this->id_user_group == 4;
  }

  public function getChildrenIdWithMyId()
  {
    $userId = [];
    if (Auth::user()->id_user_group == 4) {
      $userId = [Auth::user()->id];
    } else {
      if (Auth::user()->id_user_group == 3) {
        $resellerCreatedUserId = User::where('created_by', '=', Auth::user()->id)->pluck('id');
        $userId = $resellerCreatedUserId->push(Auth::user()->id);
      } else {
        if (Auth::user()->id_user_group == 2) {
          $adminCreatedUserId = User::where('created_by', '=', Auth::user()->id)->pluck('id');
          $resellerCreatedUserId = User::whereIn('created_by', $adminCreatedUserId)->pluck('id');
          $userId = $adminCreatedUserId->merge($resellerCreatedUserId)->push(Auth::user()->id);
        } else {
          if (Auth::user()->id_user_group == 1) {
            $supperAdminCreatedUserId = User::where('created_by', '=', Auth::user()->id)->pluck('id');
            $adminCreatedUserId = User::whereIn('created_by', $supperAdminCreatedUserId)->pluck('id');
            $resellerCreatedUserId = User::whereIn('created_by', $adminCreatedUserId)->pluck('id');
            $userId = $supperAdminCreatedUserId->merge($adminCreatedUserId)->merge($resellerCreatedUserId)->push(
              Auth::user()->id
            );
          }
        }
      }
    }

    return $userId;
  }
}
