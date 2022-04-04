<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SaloonModel extends Model
{
   protected $table='saloons';
   protected $fillable = ['owner_name','owner_mobile','owner_pan_number','owner_bank_name','owner_IFSC_code','owner_account_number','saloon_name','saloon_type_id','saloon_address','saloon_area','saloon_time_from','saloon_time_to','saloon_rating','saloon_total_seats','saloon_feature_id','owner_image','saloon_status','saloon_booking_status','admin_approval','service_id','service_price','service_time','saloon_image'];
}
