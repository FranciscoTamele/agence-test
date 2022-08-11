<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTipoUsuarioTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tipo_usuario', function (Blueprint $table) {
            $table->bigInteger("co_tipo_usuario")->default('0');
            $table->string('ds_tipo_usuario',32)->charset('utf8')->default('');
            $table->bigInteger('co_sistema')->unsigned()->default('0');
            $table->primary(['co_tipo_usuario','co_sistema']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tipo_usuario');
    }
}
