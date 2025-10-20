<?php

namespace Modules\Phonebook\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Database\Factories\GroupFactory;
use Modules\Users\App\Models\User;
use Modules\Users\App\Models\Reseller;

class Group extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'group';
    protected $fillable = ['user_id', 'name', 'type', 'status', 'reseller_id'];

    protected static function newFactory(): GroupFactory
    {
        return GroupFactory::new();
    }
    public function user()
    {
      return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function reseller()
    {
      return $this->belongsTo(Reseller::class, 'reseller_id', 'id');
    }

    public function contacts()
    {
        return $this->hasMany(Phonebook::class);
    }
}
