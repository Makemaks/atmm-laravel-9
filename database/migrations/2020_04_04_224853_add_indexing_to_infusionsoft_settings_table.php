<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexingToInfusionsoftSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('infusionsoft_settings', function (Blueprint $table) {
            $table->index('product_id', 'product_id_index');
            $table->index('subscription_plan_id', 'subscription_plan_id_index');
            $table->index('promotion_id', 'promotion_id_index');
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
          $table->dropIndex('product_id_index');
          $table->dropIndex('subscription_plan_id_index');
          $table->dropIndex('promotion_id_index');
        });
    }
}
