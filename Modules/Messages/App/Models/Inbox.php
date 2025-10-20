<?php

namespace Modules\Messages\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inbox extends Model
{
  use HasFactory;

  protected $table = 'inbox';

  protected $fillable = [
    'id',
    'sender',
    'receiver',
    'message',
    'smscount',
    'part_no',
    'total_parts',
    'reference_no',
    'read',
    'created_at',
    'updated_at'
  ];


}
