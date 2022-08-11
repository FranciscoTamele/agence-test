<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCaoFaturaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cao_fatura', function (Blueprint $table) {

            $table->integer('co_fatura')->autoIncrement();
            $table->integer('co_cliente')->default(0);
            $table->integer('co_sistema')->default(0);
            $table->integer('co_os')->default(0);
            $table->integer('num_nf')->default(0);
            $table->float('total')->default(0);
            $table->float('valor')->default(0);
            $table->date('data_emissao')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->text('corpo_nf');
            $table->float('comissao_cn')->default(0);
            $table->float('total_imp_inc')->default(0);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cao_fatura');
    }
}
