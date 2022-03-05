<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReasontocancelthesubscriptionToNmipaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
          Schema::table('nmipayments', function (Blueprint $table) {
              $table->text('reasong_to_cancel');
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
              $table->dropColumn('reasong_to_cancel');
          });
    }
}
