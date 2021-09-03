<?php

namespace App\Http\Controllers\Departamento;

use App\Http\Controllers\Controller;
use App\Http\Controllers\helperAuth;
use App\Models\Departamento\departamentosMdl;
use App\Models\Dependencia\dependenciaMdl;
use App\Models\Logs\logsMdl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;




class departamentosCtrl extends Controller
{
    public function departamentoPost(Request $request)
    {
        try {
            if ($request->id_dependencia == null) {
                $data_depen = ['id_departamento' => 1];
            } else {
                $data_depen = ['id_departamento' => $request->id_dependencia];
            }

            $id_depen = dependenciaMdl::create($data_depen)->latest('id_dependencia')->first();

            helperAuth::crearLogs($id_depen->id_dependencia, 'sci_dependencia');

            $query = departamentosMdl::where('nomenclatura', $request->nomenclatura);
            $data = [
                'descripcion' => $request->name, 'nomenclatura' => $request->nomenclatura, 'id_estado' => $request->id_estado,
                'id_dependencia' => $id_depen->id_dependencia, 'id_tipo_estructura' => $request->id_tipo_estructura
            ];

            if (helperAuth::modelHasRows($query)) {
                return helperAuth::jsonResponse(false, "El departamento ya existe!");
            } else {
                $departamento = departamentosMdl::create($data)->latest('id_departamento')->first();

                helperAuth::crearLogs($departamento->id_departamento, 'sci_estrutura_organizacion');

                return helperAuth::jsonResponse(true, "Datos almacenados correctamente");
            }
        } catch (\Throwable $th) {
            return helperAuth::jsonResponse(false, "Hubo un problema al guardar los datos");
        }
    }

    public function getDepartamento(Request $request)
    {
        $search = $request->search;
        $departamentosAll = departamentosMdl::where('id_estado', 1)->get();
        $departamento = DB::table(DB::raw('(SELECT eo.nomenclatura, eo.descripcion as departamento, d.id_dependencia, d.id_departamento as id_dept_dependencia, eo.id_departamento, eo.id_tipo_estructura, eo.id_estado FROM sci_dependencia as d
        INNER JOIN sci_estrutura_organizacion as eo
        ON d.id_dependencia = eo.id_dependencia) as rela'))
            ->join('sci_estrutura_organizacion as eo', function ($join) {
                $join->on('rela.id_dept_dependencia', '=', 'eo.id_departamento');
            })
            ->join('sci_tipo_estructura as tr', function ($join) {
                $join->on('rela.id_tipo_estructura', '=', 'tr.id_tipo_estructura');
            })
            ->join('sci_estado as es', function ($join) {
                $join->on('rela.id_estado', '=', 'es.id_estado');
            })
            ->select(
                "rela.id_departamento",
                "rela.id_dependencia",
                "rela.id_dept_dependencia",
                "eo.descripcion as dependencia",
                "rela.departamento",
                "rela.nomenclatura",
                "es.id_estado",
                "es.descripcion as estado",
                "tr.id_tipo_estructura",
                "tr.descripcion as tipoEstructura"
            )->where('rela.departamento', 'LIKE', '%' . $search . '%')->orderBy('rela.departamento', 'asc')
            ->orWhere('es.descripcion', 'LIKE', '%' . $search . '%')->orderBy('rela.departamento', 'asc')
            ->paginate(10);

        return [
            'pagination' => [
                'total'         => $departamento->total(),
                'current_page'  => $departamento->currentPage(),
                'per_page'      => $departamento->perPage(),
                'last_page'     => $departamento->lastPage(),
                'from'          => $departamento->firstItem(),
                'to'            => $departamento->lastItem()
            ],
            'departamentos' => $departamento,
            'departamentosAll' => $departamentosAll
        ];
    }

    public function putDepartamento(Request $request)
    {

        try {

            $departamentoId = $request->id_departamento;
            $data = ['descripcion' => $request->name, 'nomenclatura' => $request->nomenclatura, 'id_estado' => $request->id_estado, 'id_tipo_estructura' => $request->id_tipo_estructura, 'id_dependencia' => $request->id_dependencia];

            $dependenci = ['id_departamento' => $request->id_dept_dependencia];
            departamentosMdl::updateOrCreate(['id_departamento' => $departamentoId], $data);

            helperAuth::actualizarLogs($departamentoId, 'sci_estrutura_organizacion');

            dependenciaMdl::updateOrCreate(['id_dependencia' => $request->id_dependencia], $dependenci);

            helperAuth::actualizarLogs($request->id_dependencia, 'sci_dependencia');

            return helperAuth::jsonResponse(true, "Datos almacenados correctamente");
        } catch (\Throwable $th) {
            return helperAuth::jsonResponse(false, "Hubo un problema al guardar los datos");
        }
    }
    public function deleteDepartamento(Request $request)
    {

        $departamentoId = $request->iddepartamento;
        $status = 3;

        try {
            $departamento = departamentosMdl::find($departamentoId);
            $departamento->id_estado = $status;
            $departamento->update();

            helperAuth::eliminarLogs($departamentoId, 'sci_estrutura_organizacion');

            return helperAuth::jsonResponse(true, "El departamento ha sido modificado");
        } catch (\Throwable $th) {
            return helperAuth::jsonResponse(false, "Hubo un problema al guardar los datos");
        }
    }
}
