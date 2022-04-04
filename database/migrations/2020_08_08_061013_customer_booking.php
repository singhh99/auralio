<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CustomerBooking extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('customer_booking', function (Blueprint $table) {
            $table->increments("customer_booking_id");
            $table->string('saloon_id');
            $table->string('customer_id');
            $table->string('customer_booking_time');
            $table->string('services');
            $table->string('total_services');
            $table->string('current_status');
            $table->timestamps();
        });  
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_booking');
    }
}