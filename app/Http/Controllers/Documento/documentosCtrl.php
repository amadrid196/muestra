<?php

namespace App\Http\Controllers\Documento;

use App\Http\Controllers\Controller;
use App\Http\Controllers\helperAuth;
use App\Models\Documentos\documentosMdl;
use App\Models\Logs\logsMdl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;




class documentosCtrl extends Controller
{
    public function documentoPost(Request $request)
    {

        try {
            $query = documentosMdl::where('descripcion', $request->descripcion);
            $data = ['descripcion' => $request->name, 'evaluacion' => $request->evaluacion, 'tipos' => $request->tipos, 'id_estado' => $request->id_estado, 'autorizacion' => $request->autorizacion];

            if (helperAuth::modelHasRows($query)) {
                return helperAuth::jsonResponse(false, "El documento ya existe!");
            } else {
                $documento = documentosMdl::create($data)->latest('id_documentos')->first();

                helperAuth::crearLogs($documento->id_documentos, 'sci_documentos');

                return helperAuth::jsonResponse(true, "Datos almacenados correctamente");
            }
        } catch (\Throwable $th) {
            return helperAuth::jsonResponse(false, "Hubo un problema al guardar los datos");
        }
    }

    public function getDocumento(Request $request)
    {
        $search = $request->search;

        $documento = DB::table('sci_documentos as r')
            ->join('sci_estado as es', function ($join) {
                $join->on('r.id_estado', '=', 'es.id_estado');
            })
            ->select(
                "r.id_documentos",
                "r.descripcion",
                "r.evaluacion",
                "r.id_estado",
                "r.tipos",
                "es.descripcion as estado",
                "r.autorizacion"

            )->where('r.descripcion', 'LIKE', '%' . $search . '%')->orderBy('r.descripcion', 'desc')

            ->paginate(10);
        return [
            'pagination' => [
                'total'         => $documento->total(),
                'current_page'  => $documento->currentPage(),
                'per_page'      => $documento->perPage(),
                'last_page'     => $documento->lastPage(),
                'from'          => $documento->firstItem(),
                'to'            => $documento->lastItem()
            ],
            'documentos' => $documento
        ];
    }

    public function putDocumento(Request $request)
    {

        try {

            $documentoId = $request->iddocumento;
            $data = ['descripcion' => $request->name, 'tipos' => $request->tipos, 'evaluacion' => $request->evaluacion, 'id_estado' => $request->id_estado,  'autorizacion' => $request->autorizacion];

            documentosMdl::updateOrCreate(['id_documentos' => $documentoId], $data);

            helperAuth::actualizarLogs($documentoId, 'sci_documentos');

            return helperAuth::jsonResponse(true, "Datos almacenados correctamente");
        } catch (\Throwable $th) {
            return helperAuth::jsonResponse(false, "Hubo un problema al guardar los datos");
        }
    }
    public function deleteDocumento(Request $request)
    {

        $documentoId = $request->iddocumento;
        $status = $request->accion;

        try {
            $documento = documentosMdl::find($documentoId);
            $documento->id_estado = $status;
            $documento->update();

            helperAuth::eliminarLogs($documentoId, 'sci_documentos');

            return helperAuth::jsonResponse(true, "El documento ha sido modificado");
        } catch (\Throwable $th) {
            return helperAuth::jsonResponse(false, "Hubo un problema al guardar los datos");
        }
    }
}
