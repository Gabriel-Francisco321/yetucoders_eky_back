<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Curso;

class Usuario extends Model
{
    use SoftDeletes;

    protected $table = 'usuarios';

    protected $fillable = [
        'nome',
        'email',
        'senha',
        'tipo',
        'data_criacao',
    ];

    protected $hidden = [
        'senha',
    ];

    protected $casts = [
        'data_criacao' => 'datetime',
    ];

    
    public function cursos()
    {
        return $this->belongsToMany(Curso::class, 'curso_usuario', 'usuario_id', 'curso_id')
                    ->withTimestamps()
                    ->withPivot('deleted_at')
                    ->wherePivotNull('deleted_at');
    }
}
