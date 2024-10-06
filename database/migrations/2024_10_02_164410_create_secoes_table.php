<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSecoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('secoes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('secao')->unique();
            $table->string('local');
            $table->string('endereco');
            $table->string('bairro');
            $table->integer('aptos');
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
        Schema::dropIfExists('secoes');
    }
}