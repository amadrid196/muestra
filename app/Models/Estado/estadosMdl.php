<?php

namespace App\Models\Estado;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class estadosMdl extends Model
{
    use HasFactory;
    protected $table = "sci_estado";
    protected $primaryKey = "id_estado";

    protected $fillable = [
        "id_estado",
        "descripcion",
        "status",
    ];
    public $timestamps = false;
}
