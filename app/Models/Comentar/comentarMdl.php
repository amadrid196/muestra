<?php

namespace App\Models\Comentar;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class comentarMdl extends Model
{
    use HasFactory;
    protected $table = "sci_comentario";
    protected $primaryKey = "id_comentario";

    protected $fillable = [
        "id_comentario",
        "id_registro_documento",
        "comentario"
    ];
    public $timestamps = false;
}
