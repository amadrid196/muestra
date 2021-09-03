<?php

namespace App\Http\Controllers\Rol;

use App\Http\Controllers\Controller;
use App\Http\Controllers\helperAuth;
use App\Models\rol\rolMdl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;



class rolCtrl extends Controller
{
    public function rolPost(Request $request)
    {

        try {
            $query = rolMdl::where('descripcion', $request->name);
            $data = ['descripcion' => $request->name, 'id_estado' => $request->id_estado];

            if (helperAuth::modelHasRows($query)) {
                return helperAuth::jsonResponse(false, "El rol ya existe!");
            } else {
                $rol = rolMdl::create($data)->latest('sci_rol')->first();
                helperAuth::crearLogs($rol->id_rol, 'sci_rol');

                return helperAuth::jsonResponse(true, "Datos almacenados correctamente");
            }
        } catch (\Throwable $th) {
            return helperAuth::jsonResponse(false, "Hubo un problema al guardar los datos");
        }
    }

    public function getRol(Request $request)
    {
        $search = $request->search;

        $rol = DB::table('sci_rol as r')
            ->join('sci_estado as es', function ($join) {
                $join->on('r.id_estado', '=', 'es.id_estado');
            })
            ->select(
                "r.id_rol",
                "r.descripcion",
                "es.descripcion as estado",
                "es.id_estado"
            )->where('r.descripcion', 'LIKE', '%' . $search . '%')->orderBy('r.descripcion', 'desc')

            ->paginate(10);

        return [
            'pagination' => [
                'total'         => $rol->total(),
                'current_page'  => $rol->currentPage(),
                'per_page'      => $rol->perPage(),
                'last_page'     => $rol->lastPage(),
                'from'          => $rol->firstItem(),
                'to'            => $rol->lastItem()
            ],
            'rols' => $rol
        ];
    }

    public function putRol(Request $request)
    {
        try {

            $rolId = $request->idRol;
            $data = ['descripcion' => $request->name, 'id_estado' => $request->id_estado];

            rolMdl::updateOrCreate(['id_rol' => $rolId], $data);
            helperAuth::actualizarLogs($rolId, 'sci_rol');

            return helperAuth::jsonResponse(true, "Datos almacenados correctamente");
        } catch (\Throwable $th) {
            return helperAuth::jsonResponse(false, "Hubo un problema al guardar los datos");
        }
    }
    public function deleteRol(Request $request)
    {

        $rolId = $request->idRol;
        $status = $request->accion;

        try {
            $rol = rolMdl::find($rolId);
            $rol->id_estado = $status;
            $rol->update();
            helperAuth::eliminarLogs($rolId, 'sci_rol');

            return helperAuth::jsonResponse(true, "El rol ha sido modificado");
        } catch (\Throwable $th) {
            return helperAuth::jsonResponse(false, "Hubo un problema al guardar los datos");
        }
    }
}
