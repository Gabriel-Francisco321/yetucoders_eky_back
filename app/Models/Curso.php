<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Curso extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'cursos';

    protected $fillable = [
        'titulo',
        'descricao',
        'objectivos',
        'requisitos',
        'preco',
        'nivel',
        'id_instrutor',
        'id_categoria',
    ];

    protected $casts = [
        'preco' => 'decimal:2',
    ];

    public function instrutor(): BelongsTo
    {
        return $this->belongsTo(Instrutor::class, 'id_instrutor');
    }

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class, 'id_categoria');
    }

    public function aulas(): HasMany
    {
        return $this->hasMany(Aula::class, 'id_curso');
    }

    public function usuarios()
    {
        return $this->belongsToMany(Usuario::class, 'curso_usuario', 'curso_id', 'usuario_id')
            ->withTimestamps()
            ->withPivot('deleted_at')
            ->wherePivotNull('deleted_at');
    }
}
