<?php

namespace Modules\Phonebook\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Phonebook\Database\factories\DndFactory;

class Dnd extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'dnds';
    protected $fillable = [
                    'user_id',
                    'phone', 
                    'status'
                ];
    
    protected static function newFactory(): DndFactory
    {
        //return DndFactory::new();
    }
}
