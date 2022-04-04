<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CountryModel extends Model
{
    protected $table='countries';
    protected $fillable = ['country_name'];
}
