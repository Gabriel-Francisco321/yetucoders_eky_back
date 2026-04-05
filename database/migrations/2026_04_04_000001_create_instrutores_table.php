<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('instrutores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_usuario')->constrained('users')->onDelete('cascade');
            $table->text('biografia');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('instrutores');
    }
};
