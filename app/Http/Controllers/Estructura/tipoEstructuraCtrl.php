<?php

namespace App\Http\Controllers\Estructura;

use App\Http\Controllers\Controller;
use App\Http\Controllers\helperAuth;
use App\Models\Estructura\tipoEstructuraMdl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class tipoEstructuraCtrl extends Controller
{
    public function tipoEstructuraPost(Request $request)
    {

        try {
            $query = tipoEstructuraMdl::where('descripcion', $request->name);
            $data = ['descripcion' => $request->name, 'id_estado' => $request->id_estado];

            if (helperAuth::modelHasRows($query)) {
                return helperAuth::jsonResponse(false, "El Estructura ya existe!");
            } else {
                $tipo_estructura = tipoEstructuraMdl::create($data)->latest('id_tipo_estructura')->first();

                helperAuth::crearLogs($tipo_estructura->id_tipo_estructura, 'sci_tipo_estructura');

                return helperAuth::jsonResponse(true, "Datos almacenados correctamente");
            }
        } catch (\Throwable $th) {
            return helperAuth::jsonResponse(false, "Hubo un problema al guardar los datos");
        }
    }

    public function gettipoEstructura(Request $request)
    {
        $search = $request->search;

        $tipoEstructura = DB::table('sci_tipo_estructura as r')
            ->join('sci_estado as es', function ($join) {
                $join->on('r.id_estado', '=', 'es.id_estado');
            })
            ->select(
                "r.id_tipo_estructura",
                "r.descripcion",
                "es.descripcion as estado",
                "es.id_estado"
            )->where('r.descripcion', 'LIKE', '%' . $search . '%')->orderBy('r.descripcion', 'desc')

            ->paginate(10);
        return [
            'pagination' => [
                'total'         => $tipoEstructura->total(),
                'current_page'  => $tipoEstructura->currentPage(),
                'per_page'      => $tipoEstructura->perPage(),
                'last_page'     => $tipoEstructura->lastPage(),
                'from'          => $tipoEstructura->firstItem(),
                'to'            => $tipoEstructura->lastItem()
            ],
            'tipoEstructuras' => $tipoEstructura
        ];
    }

    public function puttipoEstructura(Request $request)
    {
        try {

            $tipoEstructuraId = $request->idtipoEstructura;
            $data = ['descripcion' => $request->name, 'id_estado' => $request->id_estado];

            tipoEstructuraMdl::updateOrCreate(['id_tipo_estructura' => $tipoEstructuraId], $data);
            helperAuth::actualizarLogs($tipoEstructuraId, 'sci_tipo_estructura');

            return helperAuth::jsonResponse(true, "Datos almacenados correctamente");
        } catch (\Throwable $th) {
            return helperAuth::jsonResponse(false, "Hubo un problema al guardar los datos");
        }
    }
    public function deletetipoEstructura(Request $request)
    {

        $tipoEstructuraId = $request->idtipoEstructura;
        $status = 3; //elimiado

        try {
            $tipoEstructura = tipoEstructuraMdl::find($tipoEstructuraId);
            $tipoEstructura->id_estado = $status;
            $tipoEstructura->update();
            helperAuth::eliminarLogs($tipoEstructuraId, 'sci_tipo_estructura');

            return helperAuth::jsonResponse(true, "La Estructura ha sido modificado");
        } catch (\Throwable $th) {
            return helperAuth::jsonResponse(false, "Hubo un problema al guardar los datos");
        }
    }
}
