<?php

namespace Modules\Users\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Modules\Smsconfig\App\Models\SenderId;
use Modules\Smsconfig\App\Models\Mask;
use Modules\Transactions\App\Models\UserWallet;
use Modules\Smsconfig\App\Models\Rate;
use Database\Factories\UserFactory;

class User extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   */
  protected $table = 'users';
  public $relations = ['userType', 'reseller', 'createBy', 'smsRate', 'emailRate', 'wallet'];

  protected $fillable = ['name', 'username' , 'password', 'email', 'address', 'mobile', 'id_user_group', 'sms_rate_id', 'tps', 'APIKEY', 'created_by', 'user_reve_api_key', 'user_reve_secret_key'];


  public function userType()
  {
    return $this->belongsTo(UserGroup::class, 'id_user_group', 'id');
  }

  protected $appends = ['is_admin', 'is_reseller', 'is_user'];
  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = [
    'password',
    'remember_token',
  ];

  /**
   * The attributes that should be cast to native types.
   *
   * @var array
   */
  protected $casts = [
    'email_verified_at' => 'datetime',
  ];

  protected static function newFactory()
  {
    return UserFactory::new();
  }

  public function reseller()
  {
    return $this->belongsTo(Reseller::class, 'reseller_id', 'id');
  }

  public function createBy()
  {
    return $this->belongsTo(User::class, 'created_by', 'id');
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

  //sender id relation
  public function senderIds()
  {
    return $this->hasMany(SenderId::class, 'user_id', 'id')->select(['id', 'senderID']);
  }

  //mask list
  public function masks()
  {
    return $this->hasMany(Mask::class, 'user_id', 'id')->select(['id', 'mask']);
  }

  //get userwise rate list
  public function rateList()
  {
    return $this->hasMany(Rate::class, 'created_by', 'id')
      ->select(['id', 'rate_name', 'masking_rate', 'nonmasking_rate']);
  }
}
