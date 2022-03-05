<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyInfusionsoftSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('infusionsoft_settings', function (Blueprint $table) {
            $table->unsignedInteger('subscription_plan_id')->after('product_id')->nullable();
            $table->boolean('isactive')->after('subscription_plan_id')->nullable(false)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('infusionsoft_settings', function (Blueprint $table) {
          $table->dropColumn('subscription_plan_id');
          $table->dropColumn('isactive');
      });
    }
}
