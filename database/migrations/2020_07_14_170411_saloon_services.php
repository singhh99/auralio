<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SaloonServices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saloons_services', function (Blueprint $table) {
            $table->increments("saloon_service_id");
            $table->string('saloon_id');
            $table->string('service_name');
            $table->string('service_price');
            $table->string('service_time');
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
        Schema::dropIfExists('saloon_services');
    }
}
