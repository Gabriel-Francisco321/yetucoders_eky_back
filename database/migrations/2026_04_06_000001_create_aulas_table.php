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
            $table->foreignId('id_curso')->constrained('cursos')->onDelete('cascade');
            $table->string('titulo');
            $table->enum('tipo', ['video', 'texto', 'pdf']);
            $table->string('conteudo_url', 2048);
            $table->unsignedInteger('duracao');
            $table->unsignedInteger('ordem');
            $table->softDeletes();
            $table->timestamps();

            $table->unique(['id_curso', 'ordem']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aulas');
    }
};
