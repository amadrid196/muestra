<?php

namespace App\Models\Sub_Categoria;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class subCategoriaMdl extends Model
{
    use HasFactory;
    protected $table = "sci_sub_categorias";
    protected $primaryKey = "id_sub_categorias";

    protected $fillable = [
        "id_sub_categorias",
        "id_categorias",
        "id_sub_tipos"
    ];
    public $timestamps = false;
}
