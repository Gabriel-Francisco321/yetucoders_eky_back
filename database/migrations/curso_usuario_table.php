use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

Schema::create('curso_usuario', function (Blueprint $table) {
    $table->foreignId('id_curso')
        ->constrained('cursos')
        ->onDelete('cascade');

    $table->foreignId('id_usuario')
        ->constrained('usuarios')
        ->onDelete('cascade');

    $table->timestamps();

    $table->primary(['id_curso', 'id_usuario']);
});
