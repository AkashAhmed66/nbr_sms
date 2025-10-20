<?php

namespace Modules\Messages\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InfozillionMessage extends Model
{
  use HasFactory;

  protected $table = 'Infozillion';
  protected $guarded = ['created_at','updated_at'];
}
