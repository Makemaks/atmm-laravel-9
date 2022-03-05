<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInfusionsoftTokenstable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('infusionsoft_tokens', function (Blueprint $table) {
            $table->increments('id');
            $table->string('access_token', 150);
            $table->string('refresh_token', 150);
            $table->string('token_type', 30);
            $table->integer('expires_in');
            $table->string('scope', 100);
            $table->timestamp('date_added')->useCurrent();
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
        Schema::dropIfExists('infusionsoft_tokens');
    }
}
