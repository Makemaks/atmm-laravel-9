<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyToInfusionsoftTrialLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('infusionsoft_trial_logs', function (Blueprint $table) {
          $table->string('product_label')->nullable();
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
          $table->dropColumn('product_label');
      });
    }
}
