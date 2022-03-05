<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUserscancelledTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::dropIfExists('userscancelleds');
      Schema::create('userscancelleds', function (Blueprint $table) {
          $table->increments('id');
          //$table->unsignedInteger('user_id')->nullable();
          $table->bigInteger('user_id')->unsigned()->index()->nullable();
          $table->string('email')->nullable();
          $table->string('reason_to_stop')->nullable();
          $table->bigInteger('original_transaction_id')->nullable(); // known as the subscription_id
          $table->timestamps();

          $table->bigInteger('subscription_id')->nullable();
          //$table->index('user_id', 'user_id_index');
          $table->foreign('user_id')->references('id')->on('users');
          $table->index('subscription_id', 'subscription_id_index');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      /*
      Schema::table('userscancelleds', function (Blueprint $table) {
        $table->dropColumn('subscription_id');
        $table->dropIndex('subscription_id_index');
        $table->dropIndex('user_id_index');
      });
      */
      Schema::dropIfExists('userscancelleds');
    }


}
