<?php

namespace Modules\Messages\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SMSRecord extends Model
{
    use HasFactory;

    protected $table = 'sms_records';
    
    protected $fillable = [
        'outbox_id',
        'status',
        'text',
        'message_id'
    ];
}
