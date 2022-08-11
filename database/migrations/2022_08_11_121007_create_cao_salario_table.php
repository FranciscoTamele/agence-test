<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCaoSalarioTable extends Migration
{




    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cao_salario', function (Blueprint $table) {

            $table->string('co_usuario',20)->default('');
            $table->date('dt_alteracao')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->float('brut_salario')->default(0);
            $table->float('liq_salario')->default(0);
            $table->primary(['co_usuario','dt_alteracao']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cao_salario');
    }
}
