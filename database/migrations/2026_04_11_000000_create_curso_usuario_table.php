<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('curso_usuario', function (Blueprint $table) {
            $table->foreignId('curso_id')
                ->constrained('cursos')
                ->onDelete('cascade');

            $table->foreignId('usuario_id')
                ->constrained('usuarios')
                ->onDelete('cascade');

            $table->timestamps();

            $table->softDeletes();

            $table->primary(['curso_id', 'usuario_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('curso_usuario');
    }
};
