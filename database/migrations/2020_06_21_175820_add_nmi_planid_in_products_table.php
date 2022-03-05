<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNmiPlanidInProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
          Schema::table('products', function (Blueprint $table) {
              $table->integer('nmi_api_plan_id')->nullable()->comment('from NMI Payment API');
          });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
          Schema::table('products', function (Blueprint $table) {
              $table->dropColumn('nmi_api_plan_id');
          });
    }
}
