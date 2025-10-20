<?php

namespace Modules\Developers\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Developers\Database\factories\DeveloperFactory;

class Developer extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];
    
    protected static function newFactory(): DeveloperFactory
    {
        //return DeveloperFactory::new();
    }
}
