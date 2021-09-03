<?php

namespace App\Models\Estructura;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tipoEstructuraMdl extends Model
{
    use HasFactory;
    protected $table = "sci_tipo_estructura";
    protected $primaryKey = "id_tipo_estructura";

    protected $fillable = [
        "id_tipo_estructura",
        "descripcion",
        "id_estado",
    ];
    public $timestamps = false;
}
