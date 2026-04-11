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
