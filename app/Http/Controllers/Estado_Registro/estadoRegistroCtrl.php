<?php

namespace App\Http\Controllers\Estado_Registro;

use App\Http\Controllers\Controller;
use App\Http\Controllers\helperAuth;
use App\Models\Estdo_Registro\estadoRegistroMdl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class estadoRegistroCtrl extends Controller
{
    public function estadoRegistroPost(Request $request)
    {

        try {
            $query = estadoRegistroMdl::where('descripcion', $request->name);
            $data = ['descripcion' => $request->name, 'status' => 1];

            if (helperAuth::modelHasRows($query)) {
                return helperAuth::jsonResponse(false, "El Estado ya existe!");
            } else {
                $estado_registro = estadoRegistroMdl::create($data)->latest('id_estado_registro_documento')->first();
                helperAuth::crearLogs($estado_registro->id_estado_registro_documento, 'sci_estado_registro_documento');

                return helperAuth::jsonResponse(true, "Datos almacenados correctamente");
            }
        } catch (\Throwable $th) {
            return helperAuth::jsonResponse(false, "Hubo un problema al guardar los datos");
        }
    }


    public function getEstadoRegistro(Request $request)
    {
        $search = $request->search;

        $estado = DB::table('sci_estado_registro_documento')->where('status', 1)
            ->select(
                "id_estado_registro_documento",
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

    public function putEstadoRegistro(Request $request)
    {
        try {

            $estadoId = $request->idEstado;
            $data = ['descripcion' => $request->name];

            estadoRegistroMdl::updateOrCreate(['id_estado_registro_documento' => $estadoId], $data);
            helperAuth::actualizarLogs($estadoId, 'sci_estado_registro_documento');

            return helperAuth::jsonResponse(true, "Datos almacenados correctamente");
        } catch (\Throwable $th) {
            return helperAuth::jsonResponse(false, "Hubo un problema al guardar los datos");
        }
    }
    public function deleteEstadoRegistro(Request $request)
    {

        $estadoId = $request->idestado;
        $status = $request->accion;

        try {
            $estado = estadoRegistroMdl::find($estadoId);
            $estado->status = $status;
            $estado->update();
            helperAuth::eliminarLogs($estadoId, 'sci_estado_registro_documento');

            return helperAuth::jsonResponse(true, "El estado ha sido modificado");
        } catch (\Throwable $th) {
            return helperAuth::jsonResponse(false, "Hubo un problema al guardar los datos");
        }
    }
}
