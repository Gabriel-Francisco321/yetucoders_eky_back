use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

Schema::create('curso_usuario', function (Blueprint $table) {
    $table->foreignId('curso_id')
        ->constrained('cursos')
        ->onDelete('cascade');

    $table->foreignId('usuario_id')
        ->constrained('usuarios')
        ->onDelete('cascade');

    $table->timestamps();

    $table->primary(['curso_id', 'usuario_id']);
});
