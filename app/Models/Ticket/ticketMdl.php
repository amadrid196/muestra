<?php

namespace App\Models\Ticket;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ticketMdl extends Model
{
    use HasFactory;

    protected $table = "sci_registro_documento";
    protected $primaryKey = "id_registro_documento";

    protected $fillable = [
        "id_registro_documento",
        "id_documento",
        "id_usuario_solicitante",
        "id_departamento_recibe",
        "fechaSolicitud",
        "asunto",
        "descripcion",
        "adjunto",
        "adjunto2",
        "id_estado"
    ];
    public $timestamps = false;
}
