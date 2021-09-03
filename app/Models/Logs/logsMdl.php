<?php

namespace App\Models\Logs;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class logsMdl extends Model
{
    use HasFactory;
    protected $table = "sci_logs";
    protected $primaryKey = "id_logs";

    protected $fillable = [
        "id_logs",
        "accion",
        "tabla",
        "id_usuario",
        "fecha",
        "id_tabla"
    ];
    public $timestamps = false;
}
