<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServiceModel extends Model
{
   protected $table='saloons_services';
   protected $fillable = ['service_name','service_price'];
}
