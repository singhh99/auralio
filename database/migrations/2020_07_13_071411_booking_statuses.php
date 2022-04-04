<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BookingStatuses extends Migration
{
   
    public function up()
    {
        Schema::create('booking_statuses', function (Blueprint $table) {
            $table->increments("booking_status_id");
            $table->string('booking_status_name');
            $table->timestamps();
        });
    }

    
    public function down()
    {
      Schema::dropIfExists('booking_statuses');
    }
}
