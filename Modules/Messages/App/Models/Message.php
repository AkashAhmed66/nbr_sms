<?php

namespace Modules\Messages\App\Models;

use App\Models\User;
use Database\Factories\SendMessageFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Phonebook\App\Models\Group;
use Modules\Smsconfig\App\Models\Rate;

class Message extends Model
{
  use HasFactory;
  protected $table = 'sentmessages';
  protected $guarded = [];
  protected $fillable = [
    'id',
    'user_id',
    'orderid',
    'source',
    'mobile_no_column',
    'message',
    'json_data',
    'senderID',
    'recipient',
    'group_id',
    'date',
    'pages',
    'status',
    'units',
    'sentFrom',
    'is_mms',
    'sms_count',
    'is_unicode',
    'IP',
    'gateway_id',
    'sms_type',
    'scheduleDateTime',
    'search_param',
    'error',
    'file',
    'priority',
    'blocked_status',
    'created_at',
    'updated_at',
    'content_type',
    'campaign_name',
    'sms_from',
    'start_time',
    'end_time',
    'sms_queued',
    'sms_processing',
    'sms_sent',
    'sms_delivered',
    'sms_failed',
    'sms_blocked',
    'is_complete',
    'is_pause',
    'archived',
    'total_recipient',
    'total_cost',
    'is_dnd_applicable',
    'client_transaction_id',
    'rn_code',
    'type',
    'long_sms',
    'is_long_sms',
    'unicode',
    'data_coding',
    'is_flash',
    'flash',
    'is_promotional',
    'is_file_processed',
    'campaign_id',
    'schedule_message_status',
    'aggregator_dlr_status_updated_from_infozilion',
    'serverTxnId',
    'serverResponseCode',
    'serverResponseMessage',
    'a2pDeliveryStatus',
    'a2pSendSmsBusinessCode',
    'deliveryStatus',
    'dndMsisdn',
    'invalidMsisdn',
    'ansSendSmsHttpStatus',
    'ansSendSmsBusinessCode',
    'mnoResponseCode',
    'mnoResponseMessage',
  ];

  protected static function newFactory()
  {
    return SendMessageFactory::new();
  }

  public function user()
  {
    return $this->belongsTo(User::class, 'user_id', 'id');
  }

  public function rate()
  {
    return $this->belongsTo(Rate::class, 'user_id', 'reseller_id');
  }

  public function smsGroup()
  {
    return $this->belongsTo(Group::class, 'group_id', 'id');
  }

  //outboxMessage
  public function outboxMessage()
  {
    return $this->hasMany(\Modules\Messages\App\Models\Outbox::class, 'reference_id', 'id');
  }
}
