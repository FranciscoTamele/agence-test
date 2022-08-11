<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCaoUsuarioTable extends Migration
{

//CREATE TABLE `cao_usuario` (






    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cao_usuario', function (Blueprint $table) {

            $table->string("co_usuario",20)->default('');
            $table->string("no_usuario",50)->default('');
            $table->string("ds_senha",14)->default('');
            $table->string('co_usuario_autorizacao',20)->default(null);
            $table->bigInteger('nu_matricula')->default(null);
            $table->date('dt_nascimento')->default(null);
            $table->date('dt_admissao_empresa')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->date('dt_desligamento')->default(null);
            $table->date('dt_inclusao')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->date('dt_expiracao')->default(null);
            $table->string('nu_cpf',14)->default(null);
            $table->string('nu_rg',20)->default(null);
            $table->string('no_orgao_emissor',10)->default(null);
            $table->string('uf_orgao_emissor',2)->default(null);
            $table->string('ds_endereco',150)->default(null);
            $table->string('no_email',100)->default(null);
            $table->string('no_email_pessoal',100)->default(null);
            $table->string('nu_telefone',64)->default(null);
            $table->dateTime('dt_alteracao')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->string('url_foto',255)->default(null);
            $table->string('instant_messenger',80)->default(null);
            $table->integer('icq')->unsigned()->default(null);
            $table->string('msn',50)->default(null);
            $table->string('yms',50)->default(null);
            $table->string('ds_comp_end',50)->default(null);
            $table->string('ds_bairro',30)->default(null);
            $table->string('nu_cep',10)->default(null);
            $table->string('no_cidade',50)->default(null);
            $table->string('uf_cidade',2)->default(null);
            $table->date('dt_expedicao')->default(null);
            $table->primary(['co_usuario']);
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
        Schema::dropIfExists('cao_usuario');
    }
}
