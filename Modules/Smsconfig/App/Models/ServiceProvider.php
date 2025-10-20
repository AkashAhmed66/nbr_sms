<?php

namespace Modules\Smsconfig\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Smsconfig\Database\factories\ServiceProviderFactory;

class ServiceProvider extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   */
  protected $table = 'channel';
    protected $fillable = [
            'name', 
            'api_provider', 
            'channel_type', 
            'created_by', 
            'url'
        ];

  protected static function newFactory(): ServiceProviderFactory
  {
    //return ServiceProviderFactory::new();
  }
}
