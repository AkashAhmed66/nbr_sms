<?php

namespace Modules\Reports\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Reports\Database\factories\ReportFactory;

class Report extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];
    
    protected static function newFactory(): ReportFactory
    {
        //return ReportFactory::new();
    }
}
