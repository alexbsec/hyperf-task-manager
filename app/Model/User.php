<?php 

namespace App\Model;

use Hyperf\DbConnection\Model\Model;

class User extends Model 
{
  protected $fillable = ['name', 'email', 'password'];
  protected $hidden = ['password'];
}

?>