<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SaloonTypes extends Migration
{
    public function up()
    {
        Schema::create('saloon_types', function (Blueprint $table) {
            $table->increments("saloon_type_id");
            $table->string('saloon_type_name');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('saloon_type');
    }
}
