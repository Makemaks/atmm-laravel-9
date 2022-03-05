<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateToInfusionsoftTrialLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('infusionsoft_trial_logs', function (Blueprint $table) {
            $table->unsignedInteger('product_id')->nullable();
            $table->index('product_id', 'product_id_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('infusionsoft_trial_logs', function (Blueprint $table) {
            $table->dropColumn('product_id');
        });
    }
}
