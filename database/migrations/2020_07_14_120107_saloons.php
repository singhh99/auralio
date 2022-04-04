<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Saloons extends Migration
{
    public function up()
    {
        Schema::create('saloons', function (Blueprint $table) {
            $table->increments("saloon_id");
            $table->string('owner_name');
            $table->string('owner_mobile');
            $table->string('owner_pan_number');
            $table->string('owner_bank_name');
            $table->string('owner_IFSC_code');
            $table->string('owner_account_number');
            $table->string('saloon_name');
            $table->string('saloon_type_id');
            $table->string('saloon_address');
            $table->string('saloon_area');
            $table->string('saloon_time_from');
            $table->string('saloon_time_to');
            $table->string('saloon_working_days');
            $table->string('saloon_rating');
            $table->string('saloon_total_seats');
            $table->string('saloon_feature_id');
            $table->string('saloon_avilable_seats');
            $table->string('saloon_avilable_slots');
            $table->integer('saloon_status')->default('0');
            $table->integer('saloon_booking_status')->default('1');
            $table->integer('admin_approval')->default('0');
            $table->integer('owner_image');
            $table->timestamps();
        });
    }

    public function down()
    {
        //
    }
}
