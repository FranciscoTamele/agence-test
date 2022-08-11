<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCaoOsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cao_os', function (Blueprint $table) {

            $table->integer('co_os')->autoIncrement();
            $table->integer('nu_os')->default(null);
            $table->integer('co_sistema')->default(null);
            $table->string('co_usuario',50)->default('0');
            $table->integer('co_arquitetura')->default(0);
            $table->string('ds_os',200)->default('0');
            $table->string('ds_caracteristica',200)->default('0');
            $table->string('ds_requisito',200)->default(null);
            $table->date('dt_inicio')->default(null);
            $table->date('dt_fim')->default(null);
            $table->integer('co_status')->default(0);
            $table->string('diretoria_sol')->default('0');
            $table->date('dt_sol')->default(null);
            $table->string('nu_tel_sol',20)->default('0');
            $table->string('ddd_tel_sol',5)->default(null);
            $table->string('nu_tel_sol2',20)->default(null);
            $table->string('ddd_tel_sol2',5)->default('0');
            $table->string('usuario_sol',50)->default('0');
            $table->date('dt_imp')->default(null);
            $table->date('dt_garantia')->default(null);
            $table->integer('co_email')->default(null);
            $table->integer('co_os_prospect_rel')->default(null);
            $table->engine='InnoDB';

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cao_os');
    }
}
