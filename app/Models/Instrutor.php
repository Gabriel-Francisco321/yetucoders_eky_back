<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Instrutor extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'instrutores';

    protected $fillable = ['id_usuario', 'biografia'];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }

    public function cursos(): HasMany
    {
        return $this->hasMany(Curso::class, 'id_instrutor');
    }
}
