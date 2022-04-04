<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PermissionModel extends Model
{
    protected $table='permissions';
    protected $fillable = ['permission_name','permission_url'];
}
