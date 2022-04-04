<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SaloonImageModel extends Model
{
   protected $table='saloons_images';
   protected $fillable = ['saloon_id','saloon_image'];
}
