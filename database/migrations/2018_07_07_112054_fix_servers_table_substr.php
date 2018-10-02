<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Server;
class FixServersTableSubstr extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('servers', function($table) {
           
        });
        
        foreach (Server::all() as $server) {

            if ((strpos($server->type, 'API')))
            {
                $server->update([
                    'type' => str_replace("API","",$server->type)
                  ]);
            }
            
            }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
