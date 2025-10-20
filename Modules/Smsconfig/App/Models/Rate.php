<?php

namespace Modules\Smsconfig\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Rate extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   */
  protected $table = 'rates';
  protected $fillable = [
    'rate_name',
    'masking_rate',
    'nonmasking_rate',
    'rate_type',
    'reseller_id',
    'created_by'
  ];
}
