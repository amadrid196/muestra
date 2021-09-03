<?php

namespace App\Models\Usuario;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class usuarioMdl extends Model
{
    use HasFactory;

    protected $table = "sci_usuarios";
    protected $primaryKey = "id_usuario";

    protected $fillable = [
        "id_usuario",
        "nombres",
        "apellidos",
        "contraseña",
        "usuario",
        "correo",
        "id_departamento",
        "id_rol",
        "id_estado"
    ];
    public $timestamps = false;
}
