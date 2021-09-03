<?php

namespace App\Models\Categoria;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class categoriaMdl extends Model
{
    use HasFactory;
    protected $table = "sci_categorias";
    protected $primaryKey = "id_categorias";

    protected $fillable = [
        "id_categorias",
        "id_registro_documento",
        "sci_tipos"
    ];
    public $timestamps = false;
}
