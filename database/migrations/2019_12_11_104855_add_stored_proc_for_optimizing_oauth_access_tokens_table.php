<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStoredProcForOptimizingOauthAccessTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('CREATE PROCEDURE RemoveOldTokens()
                        BEGIN
                            DELETE FROM oauth_access_tokens WHERE created_at < NOW() - INTERVAL 1 DAY; 
                        END 
            ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS RemoveOldTokens;');
    }
}
