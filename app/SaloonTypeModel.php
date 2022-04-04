<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SaloonTypeModel extends Model
{
    protected $table='saloon_types';
    protected $fillable = ['saloon_type_name'];
}
