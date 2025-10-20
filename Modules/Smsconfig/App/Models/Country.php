<?php

namespace Modules\Smsconfig\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Smsconfig\Database\factories\CountryFactory;

class Country extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   */
  protected $table = 'country';
  protected $fillable = ['iso', 'name', 'nickname', 'iso3', 'numcode', 'phonecode'];

  protected static function newFactory(): CountryFactory
  {
    //return CountryFactory::new();
  }
}
