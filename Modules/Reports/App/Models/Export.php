<?php

namespace Modules\Reports\App\Models;

use Illuminate\Database\Eloquent\Model;

class Export extends Model
{
    protected $fillable = [
        'user_id','format','filters','columns','rows_written',
        'status','error','storage_path','available_until'
    ];

    protected $casts = [
        'filters' => 'array',
        'columns' => 'array',
        'available_until' => 'datetime',
    ];
}
