<?php

namespace App\Models\Documentos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tipoMdl extends Model
{
    use HasFactory;
    protected $table = "sci_tipos";
    protected $primaryKey = "id_tipos";

    protected $fillable = [
        "id_tipos",
        "descripcion",
        "autorizacion",
        "id_documento",
        "id_estado",
        "sub_tipos"
    ];
    public $timestamps = false;
}
