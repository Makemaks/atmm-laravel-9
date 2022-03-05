<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateNmitransactionsCountryPostalCodeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nmitransactions', function (Blueprint $table) {
            $table->string('postal_code')->nullable()->change();
            $table->string('country')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('nmitransactions', function (Blueprint $table) {
          $table->string('postal_code')->nullable()->change();
          $table->string('country')->nullable()->change();
      });
    }
}
