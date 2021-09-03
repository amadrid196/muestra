<?php

namespace App\Models\Evaluar;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class evaluarMdl extends Model
{
    use HasFactory;
    protected $table = "sci_evaluacion";
    protected $primaryKey = "id_evaluacion";

    protected $fillable = [
        "id_evaluacion",
        "id_registro_documento",
        "id_tipo_evaluacion",
        "fecha_evaluacion",
        "comentario"
    ];
    public $timestamps = false;
}
