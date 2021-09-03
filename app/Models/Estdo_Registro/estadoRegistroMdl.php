<?php

namespace App\Models\Estdo_Registro;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class estadoRegistroMdl extends Model
{
    use HasFactory;
    protected $table = "sci_estado_registro_documento";
    protected $primaryKey = "id_estado_registro_documento";

    protected $fillable = [
        "id_estado_registro_documento",
        "descripcion",
        "status",
    ];
    public $timestamps = false;
}
