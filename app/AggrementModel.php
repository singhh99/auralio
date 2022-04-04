<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AggrementModel extends Model
{
   protected $table='salon_aggrement';
    protected $fillable = ['aggrement_type','aggrement_file'];
}
