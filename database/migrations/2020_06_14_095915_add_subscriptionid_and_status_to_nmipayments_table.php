<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSubscriptionidAndStatusToNmipaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nmipayments', function (Blueprint $table) {
            $table->unsignedInteger('subscription_id')->nullable();
            $table->string('status', 50)->nullable();
            $table->index('subscription_id', 'subscription_id_index');
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
            $table->dropIndex('subscription_id_index');
            $table->dropColumn('status');
            $table->dropColumn('subscription_id');
        });
    }
}
