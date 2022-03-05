<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOriginalTransactionIdInNmitransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('nmitransactions', function (Blueprint $table) {
          $table->bigInteger('original_transaction_id')->nullable(); // known as the subscription_id
          $table->index('original_transaction_id', 'original_transaction_id_index');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('nmitransactions', function (Blueprint $table) {
          $table->dropIndex('original_transaction_id_index');
          $table->dropColumn('original_transaction_id');
      });
    }
}
