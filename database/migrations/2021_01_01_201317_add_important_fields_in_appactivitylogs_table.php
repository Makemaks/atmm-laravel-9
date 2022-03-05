<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddImportantFieldsInAppactivitylogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('appactivitylogs', function (Blueprint $table) {
           $table->dropColumn('idToken');
        });

        Schema::table('appactivitylogs', function (Blueprint $table) {
            $table->string('ip_address', 300)->nullable();
            $table->string('device_os', 20)->after('ip_address')->nullable();
            $table->integer('device_version')->after('device_os')->nullable();
            $table->string('device_model', 70)->after('device_version')->nullable();
            $table->text('device_fullinfo')->after('device_model')->nullable();

            $table->integer('user_id')->nullable();
            $table->index('user_id', 'user_id_index');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('appactivitylogs', function (Blueprint $table) {
           $table->text('idToken')->nullable();
        });

        Schema::table('appactivitylogs', function (Blueprint $table) {
           $table->dropColumn('ip_address');
           $table->dropColumn('device_os');
           $table->dropColumn('device_version');
           $table->dropColumn('device_model');
           $table->dropColumn('device_fullinfo');

           $table->dropColumn('user_id');
           //$table->dropIndex('user_id_index');
        });

    }
}
