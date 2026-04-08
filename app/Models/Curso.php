<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Curso extends Model
{
    use SoftDeletes;
    
    protected $table    = 'cursos';
    protected $fillable = 
    [
        'titulo', 
        'descricao', 
        'objectivos', 
        'requisitos', 
        'preco', 
        'nivel', 
        'id_instrutor', 
        'id_categoria'
    ];

}
