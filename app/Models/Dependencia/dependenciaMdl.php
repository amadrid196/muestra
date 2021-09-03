<?php

namespace App\Models\Dependencia;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class dependenciaMdl extends Model
{
    use HasFactory;
    protected $table = "sci_dependencia";
    protected $primaryKey = "id_dependencia";

    protected $fillable = [
        "id_departamento",
        "id_dependencia"
    ];
    public $timestamps = false;
}
