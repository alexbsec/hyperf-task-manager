<?php

namespace App\Model;

use Hyperf\DbConnection\Model\Model;

class Task extends Model
{
  protected array $fillable = ['user_id', 'title', 'description'];

  public function user()
  {
    return $this->belongsTo(User::class);
  }
}


?>