<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
}
