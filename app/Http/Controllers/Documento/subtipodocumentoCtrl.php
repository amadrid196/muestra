<?php

namespace App\Http\Controllers\Documento;

use App\Http\Controllers\Controller;
use App\Http\Controllers\helperAuth;
use App\Models\Documentos\subtipodocumentoMdl;
use App\Models\Logs\logsMdl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class subtipodocumentoCtrl extends Controller
{
    public function subtipoDocumentoPost(Request $request)
    {

        try {
            $query = subtipodocumentoMdl::where('descripcion', $request->name);
            $data = [
                'descripcion' => $request->name, 'autorizacion' => $request->autorizacion,
                'id_estado' => $request->id_estado, 'id_tipo' => $request->id_tipo
            ];

            if (helperAuth::modelHasRows($query)) {
                return helperAuth::jsonResponse(false, "El Documento ya existe!");
            } else {
                $sub_tipo = subtipodocumentoMdl::create($data)->latest('id_sub_tipos')->first();

                helperAuth::crearLogs($sub_tipo->id_sub_tipos, 'sci_sub_tipos');

                return helperAuth::jsonResponse(true, "Datos almacenados correctamente");
            }
        } catch (\Throwable $th) {
            return helperAuth::jsonResponse(false, "Hubo un problema al guardar los datos");
        }
    }

    public function getsubtipoDocumento(Request $request)
    {
        $search = $request->search;

        $tipoDocumento = DB::table('sci_sub_tipos as r')
            ->join('sci_estado as es', function ($join) {
                $join->on('r.id_estado', '=', 'es.id_estado');
            })
            ->join('sci_tipos as doc', function ($join) {
                $join->on('r.id_tipo', '=', 'doc.id_tipos');
            })
            ->select(
                "r.id_sub_tipos",
                "r.descripcion",
                "r.autorizacion",
                "es.id_estado",
                "es.descripcion as estado",
                "doc.id_tipos",
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

    public function putsubtipoDocumento(Request $request)
    {
        try {

            $tipoDocumentoId = $request->id_sub_tipos;
            $data = [
                'descripcion' => $request->name, 'autorizacion' => $request->autorizacion,
                'id_estado' => $request->id_estado, 'id_tipo' => $request->id_tipo
            ];

            subtipodocumentoMdl::updateOrCreate(['id_sub_tipos' => $tipoDocumentoId], $data);

            helperAuth::actualizarLogs($tipoDocumentoId, 'sci_sub_tipos');

            return helperAuth::jsonResponse(true, "Datos almacenados correctamente");
        } catch (\Throwable $th) {
            return helperAuth::jsonResponse(false, "Hubo un problema al guardar los datos");
        }
    }
    public function deletesubtipoDocumento(Request $request)
    {

        $tipoDocumentoId = $request->idtipoDocumento;
        $status = 3; //elimiado

        try {
            $tipoDocumento = subtipodocumentoMdl::find($tipoDocumentoId);
            $tipoDocumento->id_estado = $status;
            $tipoDocumento->update();

            helperAuth::eliminarLogs($tipoDocumentoId, 'sci_sub_tipos');

            return helperAuth::jsonResponse(true, "La Documento ha sido modificado");
        } catch (\Throwable $th) {
            return helperAuth::jsonResponse(false, "Hubo un problema al guardar los datos");
        }
    }
}
