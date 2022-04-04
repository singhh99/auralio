<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FeatureModel extends Model
{
   protected $table='features';
    protected $fillable = ['feature_name','feature_image'];
}
