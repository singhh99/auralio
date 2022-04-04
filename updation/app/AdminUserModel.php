<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdminUserModel extends Model
{
     protected $table='users';
    protected $fillable = ['name','email','password','mobile_no'];
}
