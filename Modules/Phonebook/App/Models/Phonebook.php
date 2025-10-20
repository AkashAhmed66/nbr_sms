<?php

namespace Modules\Phonebook\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Database\Factories\PhonebookFactory;
use Modules\Phonebook\App\Models\Group;

class Phonebook extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   */
  protected $table = 'contacts';
  protected $fillable = [
    'group_id',
    'name_en',
    'name_bn',
    'phone',
    'email',
    'profession',
    'gender',
    'dob',
    'division',
    'district',
    'upazilla',
    'blood_group',
    'user_id',
    'status',
    'subscribed',
    'remarks',
    'unsubscribe_date',
    'reseller_id',
    'created_at',
    'updated_at',
  ];

  protected static function newFactory(): PhonebookFactory
  {
    return PhonebookFactory::new();
  }

  public function group()
  {
    return $this->belongsTo(Group::class, 'group_id', 'id');
  }

}
