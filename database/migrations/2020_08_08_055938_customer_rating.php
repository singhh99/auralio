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
       Schema::create('customer_rating', function (Blueprint $table) {
            $table->increments("customer_rating_id");
            $table->string('saloon_id');
            $table->string('customer_id');
            $table->string('customer_rating');
            $table->string('customer_booking_id');
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
       Schema::dropIfExists('customer_rating');
    }
}

