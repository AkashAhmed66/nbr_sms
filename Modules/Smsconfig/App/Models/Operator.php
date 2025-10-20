<?php

namespace Modules\Smsconfig\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Smsconfig\Database\factories\OperatorFactory;

class Operator extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'operator';
    protected $fillable = ['full_name', 'short_name', 'prefix', 'country_id', 'ton', 'npi'];

    protected static function newFactory(): OperatorFactory
    {
        //return OperatorFactory::new();
    }
}
