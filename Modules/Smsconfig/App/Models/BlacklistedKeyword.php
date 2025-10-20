<?php

namespace Modules\Smsconfig\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Smsconfig\Database\factories\BlacklistedKeywordFactory;
use Modules\Users\App\Models\User;

class BlacklistedKeyword extends Model
{
    use HasFactory;
    protected $table = 'keywords';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['user_id', 'title', 'keywords'];

    protected static function newFactory(): BlacklistedKeywordFactory
    {
        //return BlacklistedKeywordFactory::new();
    }

  public function user()
  {
    return $this->hasOne(User::class, 'id','user_id');
  }
}
