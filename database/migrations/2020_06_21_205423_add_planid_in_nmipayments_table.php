<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPlanidInNmipaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nmipayments', function (Blueprint $table) {
            $table->integer('plan_id')->nullable();
            $table->index('plan_id', 'plan_id_index');
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
            $table->dropIndex('plan_id_index');
            $table->dropColumn('plan_id');
        });
    }
}
