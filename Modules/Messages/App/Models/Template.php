<?php

namespace Modules\Messages\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Database\Factories\TemplateFactory;
use Modules\Users\App\Models\User;

class Template extends Model
{
  use HasFactory;

  protected $table = 'template';
  protected $fillable = ['title', 'description', 'user_id'];

  protected static function newFactory()
  {
    return TemplateFactory::new();
  }
  public function user()
  {
    return $this->belongsTo(User::class, 'user_id', 'id');
  }
}
