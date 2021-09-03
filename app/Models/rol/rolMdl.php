<?php

namespace App\Models\rol;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class rolMdl extends Model
{
    use HasFactory;
    protected $table = "sci_rol";
    protected $primaryKey = "id_rol";

    protected $fillable = [
        "id_rol",
        "descripcion",
        "id_estado",
    ];
    public $timestamps = false;
}
