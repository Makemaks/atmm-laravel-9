<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateInfusionsoftLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('infusionsoft_logs', function (Blueprint $table) {
            $table->integer('contact_id')->after('id')->nullable();
            $table->integer('creditcard_id')->after('contact_id')->nullable();
            $table->integer('order_id')->after('creditcard_id')->nullable();
            $table->integer('invoice_id')->after('order_id')->nullable();
            //$table->unsignedInteger('user_id')->after('id')->nullable();
            $table->bigInteger('user_id')->unsigned()->index()->nullable();
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
        Schema::table('infusionsoft_logs', function (Blueprint $table) {
            $table->dropColumn('contact_id');
            $table->dropColumn('creditcard_id');
            $table->dropColumn('order_id');
            $table->dropColumn('invoice_id');
            //$table->dropForeign('user_id');
            //$table->dropColumn('user_id');
        });
    }
}
