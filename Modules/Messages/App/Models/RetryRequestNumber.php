<?php

namespace Modules\Messages\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RetryRequestNumber extends Model
{
  use HasFactory;

  protected $table = 'retry_request_number';
  protected $fillable = ['mobile', 'retry_at'];
}
