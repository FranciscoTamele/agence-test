<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCaoClienteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cao_cliente', function (Blueprint $table) {

            $table->integer('co_cliente')->autoIncrement();
            $table->string('no_razao',50)->default(null);
            $table->string('no_fantasia',50)->default(null);
            $table->string('no_contato',50)->default(null);
            $table->string('nu_telefone',50)->default(null);
            $table->string('nu_ramal',50)->default(null);
            $table->string('nu_cnpj',150)->default(null);
            $table->string('ds_endereco',6)->default(null);
            $table->integer('nu_numero')->default(null);
            $table->string('ds_complemento',150)->default(null);
            $table->string('no_bairro',150)->default('');
            $table->string('nu_cep',10)->default(null);
            $table->string('no_pais',50)->default('');
            $table->bigInteger('co_ramo')->default(null);
            $table->bigInteger('co_cidade')->default(null);
            $table->integer('co_status')->unsigned()->default(null);
            $table->string('ds_site',50)->default(null);
            $table->string('ds_email',50)->default(null);
            $table->string('ds_cargo_contato',50)->default(null);
            $table->char('tp_cliente',2)->default(null);
            $table->string('ds_referencia',100)->default(null);
            $table->integer('co_complemento_status')->unsigned()->default(null);
            $table->string('nu_fax',15)->default(null);
            $table->string('ddd2',10)->default(null);
            $table->string('telefone2',20)->default(null);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cao_cliente');
    }
}
