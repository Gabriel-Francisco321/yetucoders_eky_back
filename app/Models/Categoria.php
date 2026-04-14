<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Categoria extends Model
{
    use SoftDeletes;

    protected $table = 'categorias';

    protected $fillable = ['titulo', 'descricao'];

    public function cursos(): HasMany
    {
        return $this->hasMany(Curso::class, 'id_categoria');
    }
}
