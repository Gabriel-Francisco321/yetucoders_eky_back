<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Avaliacao extends Model
{
    protected $table = "avaliacao";
    protected $fillable = [
        "nota",
        "comentário",
        "id_usuario",
        "id_curso",
    ];

    public function usuario(){
        return $this->belongsTo(Usuario::class, "id_usuario");
    }
}
