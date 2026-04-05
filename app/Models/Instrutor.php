<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Instrutor extends Model
{
    use SoftDeletes;

    protected $table    = 'instrutores';
    protected $fillable = ['id_usuario', 'biografia'];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }
}
