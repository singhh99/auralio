<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AdminUser extends Migration
{
    
    public function up()
    {
       Schema::create('admin_users', function (Blueprint $table) {
            $table->increments("user_id");
            $table->string('user_name');
            $table->string('user_mobile');
            $table->string('user_email');
            $table->string('user_password');
            $table->string('city_id');
            $table->string('user_address')->default('CURRENT_TIMESTAMP');

            $table->timestamps();
        });
    }

    
    public function down()
    {
        Schema::dropIfExists('admin_users');
    }
}
