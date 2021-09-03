<?php

namespace App\Http\Controllers\comentar;

use App\Http\Controllers\Controller;
use App\Http\Controllers\helperAuth;
use App\Models\Comentar\comentarMdl;
use App\Models\Logs\logsMdl;
use App\Models\Registro_Documento\registroDocumentoMdl;
use Illuminate\Http\Request;

class comentarCtrl extends Controller
{
    public function comentarPost(Request $request)
    {

        try {
            $query = comentarMdl::where('id_registro_documento', $request->id);
            $data = ['comentario' => $request->name, 'id_registro_documento' => $request->id];

            if (helperAuth::modelHasRows($query)) {
                return helperAuth::jsonResponse(false, "Ya existe un comentario!");
            } else {
                $comentario = comentarMdl::create($data)->latest('id_comentario')->first();
                helperAuth::crearLogs($comentario->id_comentario, 'sci_comentario');

                $estado = registroDocumentoMdl::find($request->id);
                $estado->id_estado_registro = 2; //estado en proceso
                $estado->update();
                helperAuth::actualizarLogs($request->id, 'sci_registro_documento');

                return helperAuth::jsonResponse(true, "Datos almacenados correctamente");
            }
        } catch (\Throwable $th) {
            return helperAuth::jsonResponse(false, "Hubo un problema al guardar los datos");
        }
    }
}
