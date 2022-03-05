<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateNmitransactionsOriginalTransactionIdProcessorResponseCodeRequestedAmountTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nmitransactions', function (Blueprint $table) {
            $table->string('original_transaction_id')->nullable()->change();
            $table->string('requested_amount')->nullable()->change();
            $table->string('processor_response_code')->nullable()->change();
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
            $table->string('original_transaction_id')->nullable()->change();
            $table->string('requested_amount')->nullable()->change();
            $table->string('processor_response_code')->nullable()->change();
        });
    }
}
