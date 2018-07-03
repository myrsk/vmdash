<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('servers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('hostname');
            $table->string('ipv4');
            $table->integer('sshport');
            $table->text('sshkey')->nullable();
            $table->string('type')->nullable();
            $table->string('apiserverid')->nullable();
            $table->string('apiurl')->nullable();
            $table->string('apikey')->nullable();
            $table->string('apipass')->nullable();
            $table->string('provider');
            $table->string('provider_url');
            $table->string('location');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('servers');
    }
}
