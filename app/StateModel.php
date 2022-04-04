<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StateModel extends Model
{
    protected $table='states';
    protected $fillable = ['country_id','state_name'];
}
