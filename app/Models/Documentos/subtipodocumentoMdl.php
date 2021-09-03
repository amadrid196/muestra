<?php

namespace App\Models\Documentos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class subtipodocumentoMdl extends Model
{
    use HasFactory;
    protected $table = "sci_sub_tipos";
    protected $primaryKey = "id_sub_tipos";

    protected $fillable = [
        "id_sub_tipos",
        "descripcion",
        "autorizacion",
        "id_estado",
        "id_tipo"
    ];
    public $timestamps = false;
}
