<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CityModel extends Model
{
    protected $table='cities';
    protected $fillable = ['city_name','state_id'];
}
