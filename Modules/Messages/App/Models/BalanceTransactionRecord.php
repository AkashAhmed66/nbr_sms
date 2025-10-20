<?php

namespace Modules\Messages\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BalanceTransactionRecord extends Model
{
    use HasFactory;

    protected $table = 'balance_transaction_records';

    protected $fillable = [
      'client_trans_id',
      'created_at',
      'user_id'
    ];
}
