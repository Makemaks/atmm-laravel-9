<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateOtpCodeOtpCodeGenerateAtInUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('users', function (Blueprint $table) {
        $table->integer('otp_code')->nullable()->change();
        $table->dateTime('otp_code_generate_at')->nullable()->change();
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('users', function (Blueprint $table) {
        $table->integer('otp_code')->nullable()->change();
        $table->dateTime('otp_code_generate_at')->nullable()->change();
      });
    }
}
