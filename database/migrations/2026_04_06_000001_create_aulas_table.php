<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aulas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_curso');
            $table->string('titulo');
            $table->enum('tipo', ['video', 'texto', 'pdf']);
            $table->string('conteudo_url', 2048);
            $table->unsignedInteger('duracao');
            $table->unsignedInteger('ordem');
            $table->timestamps();

            $table->index('id_curso');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aulas');
    }
};
