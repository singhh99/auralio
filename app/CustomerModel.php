<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerModel extends Model
{
    protected $table='customers';
    protected $fillable = ['customer_name','customer_email','customer_gender','customer_image','referal_by','referal_code','customer_total_referal'];
}
