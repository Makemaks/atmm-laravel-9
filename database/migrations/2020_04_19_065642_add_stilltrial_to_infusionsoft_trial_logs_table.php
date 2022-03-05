<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStilltrialToInfusionsoftTrialLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('infusionsoft_trial_logs', function (Blueprint $table) {
            $table->boolean('still_trial')->nullable(false)->default(1);
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
          $table->dropColumn('still_trial');
      });
    }
}
