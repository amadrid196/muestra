<?php

namespace App\Http\Controllers\Documento;

use App\Http\Controllers\Controller;
use App\Http\Controllers\helperAuth;
use App\Models\Documentos\tipoMdl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class tipoCtrl extends Controller
{
    public function tipoDocumentoPost(Request $request)
    {

        try {
            $query = tipoMdl::where('descripcion', $request->name);
            $data = [
                'descripcion' => $request->name, 'autorizacion' => $request->autorizacion, 'id_documento' => $request->id_documento,
                'id_estado' => $request->id_estado, 'sub_tipos' => $request->sub_tipos
            ];

            if (helperAuth::modelHasRows($query)) {
                return helperAuth::jsonResponse(false, "El Tipo de Documento ya existe!");
            } else {
                $tipos = tipoMdl::create($data)->latest('id_tipos')->first();

                helperAuth::crearLogs($tipos->id_tipos, 'sci_tipos');

                return helperAuth::jsonResponse(true, "Datos almacenados correctamente");
            }
        } catch (\Throwable $th) {
            return helperAuth::jsonResponse(false, "Hubo un problema al guardar los datos");
        }
    }

    public function gettipoDocumento(Request $request)
    {
        $search = $request->search;

        $tipoDocumento = DB::table('sci_tipos as r')
            ->join('sci_estado as es', function ($join) {
                $join->on('r.id_estado', '=', 'es.id_estado');
            })
            ->join('sci_documentos as doc', function ($join) {
                $join->on('r.id_documento', '=', 'doc.id_documentos');
            })
            ->select(
                "r.id_tipos",
                "r.descripcion",
                "r.autorizacion",
                "r.id_documento",
                "r.sub_tipos",
                "es.id_estado",
                "es.descripcion as estado",
                "doc.descripcion as documento"
            )->where('r.descripcion', 'LIKE', '%' . $search . '%')->orderBy('r.descripcion', 'desc')

            ->paginate(10);
        return [
            'pagination' => [
                'total'         => $tipoDocumento->total(),
                'current_page'  => $tipoDocumento->currentPage(),
                'per_page'      => $tipoDocumento->perPage(),
                'last_page'     => $tipoDocumento->lastPage(),
                'from'          => $tipoDocumento->firstItem(),
                'to'            => $tipoDocumento->lastItem()
            ],
            'tipoDocumentos' => $tipoDocumento
        ];
    }

    public function puttipoDocumento(Request $request)
    {
        try {

            $tipoDocumentoId = $request->idtipodocumento;
            $data = [
                'descripcion' => $request->name, 'autorizacion' => $request->autorizacion, 'id_documento' => $request->id_documento,
                'id_estado' => $request->id_estado, 'sub_tipos' => $request->sub_tipos
            ];

            tipoMdl::updateOrCreate(['id_tipos' => $tipoDocumentoId], $data);
            helperAuth::actualizarLogs($tipoDocumentoId, 'sci_tipos');

            return helperAuth::jsonResponse(true, "Datos almacenados correctamente");
        } catch (\Throwable $th) {
            return helperAuth::jsonResponse(false, "Hubo un problema al guardar los datos");
        }
    }
    public function deletetipoDocumento(Request $request)
    {

        $tipoDocumentoId = $request->idtipoDocumento;
        $status = 3; //elimiado

        try {
            $tipoDocumento = tipoMdl::find($tipoDocumentoId);
            $tipoDocumento->id_estado = $status;
            $tipoDocumento->update();
            helperAuth::actualizarLogs($tipoDocumentoId, 'sci_tipos');

            return helperAuth::jsonResponse(true, "La Documento ha sido modificado");
        } catch (\Throwable $th) {
            return helperAuth::jsonResponse(false, "Hubo un problema al guardar los datos");
        }
    }
}
