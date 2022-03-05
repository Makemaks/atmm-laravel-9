<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSalesTaxToInfusionsoftSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('infusionsoft_settings', function (Blueprint $table) {
            $table->float('sales_tax', 8, 4)->nullable(false)->default(0.0567);
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
          $table->dropColumn('sales_tax');
      });
    }
}
