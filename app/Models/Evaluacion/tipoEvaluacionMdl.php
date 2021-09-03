<?php

namespace App\Models\Evaluacion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tipoEvaluacionMdl extends Model
{
    use HasFactory;
    protected $table = "sci_tipo_evaluacion";
    protected $primaryKey = "id_tipo_evaluacion";

    protected $fillable = [
        "id_tipo_evaluacion",
        "descripcion",
        "id_estado",
    ];
    public $timestamps = false;
}
