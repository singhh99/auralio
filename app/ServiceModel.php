<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServiceModel extends Model
{
   protected $table='services';
   protected $fillable = ['service_name'];
}
