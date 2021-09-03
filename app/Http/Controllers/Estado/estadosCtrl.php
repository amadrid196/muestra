<?php

namespace App\Http\Controllers\Estado;

use App\Http\Controllers\Controller;
use App\Models\Estado\estadosMdl;
use Illuminate\Http\Request;
use App\Http\Controllers\helperAuth;
use App\Models\Logs\logsMdl;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class estadosCtrl extends Controller
{
    public function estadoPost(Request $request)
    {

        try {
            $query = estadosMdl::where('descripcion', $request->name);
            $data = ['descripcion' => $request->name, 'status' => 1];

            if (helperAuth::modelHasRows($query)) {
                return helperAuth::jsonResponse(false, "El Estado ya existe!");
            } else {
                $estado = estadosMdl::create($data)->latest('id_estado')->first();
                helperAuth::crearLogs($estado->id_estado, 'sci_estado');

                return helperAuth::jsonResponse(true, "Datos almacenados correctamente");
            }
        } catch (\Throwable $th) {
            return helperAuth::jsonResponse(false, "Hubo un problema al guardar los datos");
        }
    }


    public function getEstado(Request $request)
    {
        $search = $request->search;

        $estado = DB::table('sci_estado')->where('status', 1)
            ->select(
                "id_estado",
                "descripcion",
            )->where('descripcion', 'LIKE', '%' . $search . '%')->orderBy('descripcion', 'desc')

            ->paginate(10);
        return [
            'pagination' => [
                'total'         => $estado->total(),
                'current_page'  => $estado->currentPage(),
                'per_page'      => $estado->perPage(),
                'last_page'     => $estado->lastPage(),
                'from'          => $estado->firstItem(),
                'to'            => $estado->lastItem()
            ],
            'estados' => $estado
        ];
    }

    public function putEstado(Request $request)
    {
        try {

            $estadoId = $request->idEstado;
            $data = ['descripcion' => $request->name];

            estadosMdl::updateOrCreate(['id_estado' => $estadoId], $data);
            helperAuth::actualizarLogs($estadoId, 'sci_estado');

            return helperAuth::jsonResponse(true, "Datos almacenados correctamente");
        } catch (\Throwable $th) {
            return helperAuth::jsonResponse(false, "Hubo un problema al guardar los datos");
        }
    }
    public function deleteEstado(Request $request)
    {

        $estadoId = $request->idestado;
        $status = $request->accion;

        try {
            $estado = estadosMdl::find($estadoId);
            $estado->status = $status;
            $estado->update();
            helperAuth::eliminarLogs($estadoId, 'sci_estado');
            return helperAuth::jsonResponse(true, "El estado ha sido modificado");
        } catch (\Throwable $th) {
            return helperAuth::jsonResponse(false, "Hubo un problema al guardar los datos");
        }
    }
}
