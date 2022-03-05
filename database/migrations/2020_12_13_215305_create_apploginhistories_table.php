<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApploginhistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('apploginhistories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ip_address', 300)->nullable();
            $table->string('browser_info', 200)->nullable();
            $table->string('device', 20)->nullable();
            $table->boolean('is_logout')->nullable(false)->default(0);
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
        Schema::dropIfExists('apploginhistories');
    }
}
