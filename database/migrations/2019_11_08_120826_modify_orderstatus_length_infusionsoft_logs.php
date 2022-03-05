<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyOrderstatusLengthInfusionsoftLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('infusionsoft_logs', function (Blueprint $table) {
            $table->dropColumn('order_status');
        });

        Schema::table('infusionsoft_logs', function (Blueprint $table) {
            $table->string('order_status',30)->after('invoice_id')->nullable();
            //$table->string('order_status',30)->after('invoice_id')->nullable()->change();
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
            $table->dropColumn('order_status');
        });
    }
}
