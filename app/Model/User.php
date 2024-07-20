<?php 

namespace App\Model;

use Hyperf\DbConnection\Model\Model;

class User extends Model 
{
  protected array $fillable = ['name', 'email', 'password'];
  protected array $hidden = ['password'];
}

?>