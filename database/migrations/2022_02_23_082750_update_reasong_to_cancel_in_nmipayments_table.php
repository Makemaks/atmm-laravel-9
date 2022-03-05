<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateReasongToCancelInNmipaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nmipayments', function (Blueprint $table) {
          $table->string('reasong_to_cancel')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('nmipayments', function (Blueprint $table) {
        $table->string('reasong_to_cancel')->nullable()->change();
      });
    }
}
