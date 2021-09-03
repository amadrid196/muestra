<?php

namespace App\Http\Controllers\Evaluar;

use App\Http\Controllers\Controller;
use App\Http\Controllers\helperAuth;
use App\Models\Evaluar\evaluarMdl;
use App\Models\Registro_Documento\registroDocumentoMdl;
use Illuminate\Http\Request;

class evaluarCtrl extends Controller
{
    public function evaluarPost(Request $request)
    {

        try {
            $fecha = '';
            $query = evaluarMdl::where('id_registro_documento', $request->id_registro_documento)->get();
            $data = [
                'comentario' => $request->comentario, 'id_registro_documento' => $request->id_registro_documento,
                'id_tipo_evaluacion' => $request->id_tipo_evaluacion
            ];

            if (helperAuth::modelHasRows($query)) {
                $evaluar = evaluarMdl::updateOrCreate(['id_evaluacion' => $query[0]->id_evaluacion], $data);
                $estado = registroDocumentoMdl::find($request->id_registro_documento);
                if ($request->accion == '4') {
                    $estado->id_estado_registro = 2; //estado en proceso
                } else {
                    $estado->id_estado_registro = 4; //estado en finalizado
                }
                $estado->update();
                return helperAuth::jsonResponse(true, "Datos almacenados correctamente");
            } else {
                $evaluar = evaluarMdl::create($data)->latest('id_evaluacion')->first();
                helperAuth::crearLogs($evaluar->id_evaluacion, 'sci_evaluacion');

                $estado = registroDocumentoMdl::find($request->id_registro_documento);
                if ($request->accion == '4') {
                    $estado->id_estado_registro = 2; //estado en proceso
                } else {
                    $estado->id_estado_registro = 4; //estado en finalizado
                }
                $estado->update();
                helperAuth::actualizarLogs($request->id_registro_documento, 'sci_registro_documento');

                return helperAuth::jsonResponse(true, "Datos almacenados correctamente");
            }
        } catch (\Throwable $th) {
            return helperAuth::jsonResponse(false, "Hubo un problema al guardar los datos");
        }
    }
}
