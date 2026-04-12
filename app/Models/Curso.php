<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Usuario;

class Curso extends Model
{
    use SoftDeletes;
    
    protected $table = 'cursos';

    protected $fillable = [
        'titulo', 
        'descricao', 
        'objectivos', 
        'requisitos', 
        'preco', 
        'nivel', 
        'id_instrutor', 
        'id_categoria'
    ];

    // RELACIONAMENTO Kytdzz
    public function usuarios()
    {
        return $this->belongsToMany(
            Usuario::class,
            'curso_usuario',
            'curso_id',
            'usuario_id'
        )
        ->withTimestamps()
        ->withPivot('deleted_at')
        ->wherePivotNull('deleted_at');
    }
}
