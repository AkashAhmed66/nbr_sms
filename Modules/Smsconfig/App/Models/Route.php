<?php

namespace Modules\Smsconfig\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Smsconfig\Database\factories\RouteFactory;
use Modules\Users\App\Models\User;

class Route extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'routes';
    protected $fillable = ['user_id', 'has_mask', 'operator_prefix', 'channel_id', 'cost', 'success_rate', 'default_mask'];

    protected static function newFactory(): RouteFactory
    {
        //return RouteFactory::new();
    }

  public function user()
  {
    return $this->belongsTo(User::class, 'user_id', 'id');
  }

  public function channel()
  {
    return $this->belongsTo(ServiceProvider::class, 'channel_id', 'id');
  }
}
