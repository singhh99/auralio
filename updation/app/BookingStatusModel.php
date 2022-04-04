<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BookingStatusModel extends Model
{
    protected $table='booking_statuses';
    protected $fillable = ['booking_status_name'];
}
