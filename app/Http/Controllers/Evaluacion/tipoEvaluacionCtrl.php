<?php

namespace App\Http\Controllers\Evaluacion;

use App\Http\Controllers\Controller;
use App\Http\Controllers\helperAuth;
use App\Models\Evaluacion\tipoEvaluacionMdl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class tipoEvaluacionCtrl extends Controller
{
    public function tipoEvaluacionPost(Request $request)
    {

        try {
            $query = tipoEvaluacionMdl::where('descripcion', $request->name);
            $data = ['descripcion' => $request->name, 'id_estado' => $request->id_estado];

            if (helperAuth::modelHasRows($query)) {
                return helperAuth::jsonResponse(false, "El Evaluacion ya existe!");
            } else {
                $tipo_evaluacion = tipoEvaluacionMdl::create($data)->latest('id_tipo_evaluacion')->first();
                helperAuth::crearLogs($tipo_evaluacion->id_tipo_evaluacion, 'sci_tipo_evaluacion');

                return helperAuth::jsonResponse(true, "Datos almacenados correctamente");
            }
        } catch (\Throwable $th) {
            return helperAuth::jsonResponse(false, "Hubo un problema al guardar los datos");
        }
    }

    public function gettipoEvaluacion(Request $request)
    {
        $search = $request->search;

        $tipoEvaluacion = DB::table('sci_tipo_evaluacion as r')
            ->join('sci_estado as es', function ($join) {
                $join->on('r.id_estado', '=', 'es.id_estado');
            })
            ->select(
                "r.id_tipo_evaluacion",
                "r.descripcion",
                "r.id_estado",
                "r.icon",
                "es.descripcion as estado"
            )->where('r.descripcion', 'LIKE', '%' . $search . '%')->orderBy('r.id_tipo_evaluacion', 'asc')

            ->paginate(10);
        return [
            'pagination' => [
                'total'         => $tipoEvaluacion->total(),
                'current_page'  => $tipoEvaluacion->currentPage(),
                'per_page'      => $tipoEvaluacion->perPage(),
                'last_page'     => $tipoEvaluacion->lastPage(),
                'from'          => $tipoEvaluacion->firstItem(),
                'to'            => $tipoEvaluacion->lastItem()
            ],
            'tipoEvaluacions' => $tipoEvaluacion
        ];
    }

    public function puttipoEvaluacion(Request $request)
    {
        try {

            $tipoEvaluacionId = $request->idtipoEvaluacion;
            $data = ['descripcion' => $request->name, 'id_estado' => $request->id_estado];

            tipoEvaluacionMdl::updateOrCreate(['id_tipo_evaluacion' => $tipoEvaluacionId], $data);
            helperAuth::actualizarLogs($tipoEvaluacionId, 'sci_tipo_evaluacion');

            return helperAuth::jsonResponse(true, "Datos almacenados correctamente");
        } catch (\Throwable $th) {
            return helperAuth::jsonResponse(false, "Hubo un problema al guardar los datos");
        }
    }
    public function deletetipoEvaluacion(Request $request)
    {

        $tipoEvaluacionId = $request->idtipoEvaluacion;
        $status = 3; //elimiado

        try {
            $tipoEvaluacion = tipoEvaluacionMdl::find($tipoEvaluacionId);
            $tipoEvaluacion->id_estado = $status;
            $tipoEvaluacion->update();
            helperAuth::actualizarLogs($tipoEvaluacionId, 'sci_tipo_evaluacion');

            return helperAuth::jsonResponse(true, "La Evaluacion ha sido modificado");
        } catch (\Throwable $th) {
            return helperAuth::jsonResponse(false, "Hubo un problema al guardar los datos");
        }
    }
}
