<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SaloonImages extends Migration
{
    
    public function up()
    {
            Schema::create('saloons_images', function (Blueprint $table) {
            $table->increments("saloon_image_id");
            $table->integer('saloon_id');
            $table->string('saloon_image');
            $table->timestamps();
        });    

    }

   
    public function down()
    {
        Schema::dropIfExists('saloon_images');
    }
}
