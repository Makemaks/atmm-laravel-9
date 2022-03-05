<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSubscriptionidInNmipaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nmipayments', function (Blueprint $table) {
            $table->bigInteger('subscription_id')->nullable()->change();
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
          $table->unsignedInteger('subscription_id')->nullable()->change();
      });
    }
}
