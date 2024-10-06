<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApuracaoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('apuracao', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('candidato_id')->constrained('candidatos')->onDelete('cascade');
            $table->foreignId('secao_id')->constrained('secoes')->onDelete('cascade');
            $table->integer('votos');
            $table->timestamps();

            // Adiciona a chave única composta
            $table->unique(['candidato_id', 'secao_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('apuracao');
    }
}