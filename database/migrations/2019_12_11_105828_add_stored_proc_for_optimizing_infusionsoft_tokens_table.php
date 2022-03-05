<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStoredProcForOptimizingInfusionsoftTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
            CREATE PROCEDURE RemoveInfusionSoftOldTokens()
            BEGIN
                DECLARE inftokencount varchar(16);
                SELECT COUNT(id) INTO inftokencount FROM infusionsoft_tokens;
                IF inftokencount > 5
                    THEN
                        SET @myvar := (SELECT GROUP_CONCAT(id SEPARATOR \',\') AS myval FROM (SELECT id FROM infusionsoft_tokens ORDER BY id DESC LIMIT 5 ) A );
                        DELETE FROM infusionsoft_tokens WHERE NOT FIND_IN_SET(id,@myvar);
                END IF;
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
        DB::unprepared('DROP PROCEDURE IF EXISTS RemoveInfusionSoftOldTokens;');
    }
}
