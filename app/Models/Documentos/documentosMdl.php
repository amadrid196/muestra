<?php

namespace App\Models\Documentos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class documentosMdl extends Model
{
    use HasFactory;
    protected $table = "sci_documentos";
    protected $primaryKey = "id_documentos";

    protected $fillable = [
        "id_documentos",
        "descripcion",
        "evaluacion",
        "id_estado",
        "tipos",
        "autorizacion"
    ];
    public $timestamps = false;
}
