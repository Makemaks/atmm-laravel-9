<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSubscriptionPlanIdToInfusionsoftTrialLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('infusionsoft_trial_logs', function (Blueprint $table) {
            $table->unsignedInteger('subscription_plan_id')->nullable();
            $table->index('subscription_plan_id', 'subscription_plan_id_index');
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
            $table->dropIndex('subscription_plan_id_index');
            $table->dropColumn('subscription_plan_id');
        });
    }
}
