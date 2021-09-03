<?php

namespace App\Models\Departamento;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class departamentosMdl extends Model
{
    use HasFactory;
    protected $table = "sci_estrutura_organizacion";
    protected $primaryKey = "id_departamento";

    protected $fillable = [
        "id_departamento",
        "descripcion",
        "nomenclatura",
        "id_estado",
        "id_tipo_estructura",
        "id_dependencia"
    ];
    public $timestamps = false;
}
