<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNmipaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nmipayments', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('response')->nullable();
            $table->bigInteger('transactionid')->nullable();
            $table->string('responsetext', 200)->nullable();
            $table->string('orderid', 100)->nullable();
            $table->integer('response_code')->nullable();
            $table->mediumText('raw_data')->nullable();

            $table->string('email', 255)->nullable();
            //$table->unsignedInteger('user_id')->nullable();
            $table->bigInteger('user_id')->unsigned()->index()->nullable();

            $table->timestamps();
            $table->index('email');
            $table->index('transactionid');
            $table->foreign('user_id')->references('id')->on('users');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nmipayments');
    }
}
