<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Curso;

class Aula extends Model
{
    protected $table = 'aulas';

    protected $fillable = [
        'id_curso',
        'titulo',
        'tipo',
        'conteudo_url',
        'duracao',
        'ordem',
    ];

    protected $casts = [
        'id_curso' => 'integer',
        'duracao' => 'integer',
        'ordem' => 'integer',
    ];

    public function curso(): BelongsTo
    {
        return $this->belongsTo(Curso::class, 'id_curso');
    }
}
