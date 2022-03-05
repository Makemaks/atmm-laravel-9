<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNmitransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nmitransactions', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('transaction_id')->nullable();
            $table->string('transaction_type', 10)->nullable();
            $table->string('condition', 20)->nullable();
            $table->string('order_id', 30)->nullable();
            $table->string('ponumber', 30)->nullable();
            $table->string('first_name',100)->nullable();
            $table->string('last_name',100)->nullable();
            $table->string('address_1')->nullable();
            $table->string('city',50)->nullable();
            $table->string('state',50)->nullable();
            $table->mediumInteger('postal_code')->nullable();
            $table->string('country',10)->nullable();
            $table->string('email')->nullable();
            $table->string('phone',20)->nullable();
            $table->decimal('amount', 8, 2)->nullable();
            $table->string('action_type', 20)->nullable();
            $table->string('date', 30)->nullable();
            $table->string('date_formatted', 50)->nullable();
            $table->boolean('success')->nullable();
            $table->string('source', 40)->nullable();
            $table->string('username', 50)->nullable();
            $table->string('response_text')->nullable();
            $table->mediumInteger('response_code')->nullable();
            $table->string('processor_response_text', 40)->nullable();
            $table->mediumInteger('processor_response_code')->nullable();
            $table->decimal('requested_amount', 8, 2)->nullable();
            $table->timestamps();
            $table->index('transaction_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nmitransactions');
    }
}
