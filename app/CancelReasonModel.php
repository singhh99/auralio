<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CancelReasonModel extends Model
{
    protected $table='cancellation_reasons';
    protected $fillable = ['reason_name'];
}
