<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppactivitylogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appactivitylogs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('apiroute', 200)->nullable();
            $table->string('apiroutename', 100)->nullable();
            $table->text('idToken')->nullable();
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
        Schema::dropIfExists('appactivitylogs');
    }
}
