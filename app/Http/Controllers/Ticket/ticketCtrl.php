<?php

namespace App\Http\Controllers\Ticket;

use App\Http\Controllers\Controller;
use App\Http\Controllers\helperAuth;
use App\Models\Categoria\categoriaMdl;
use App\Models\Departamento\departamentosMdl;
use Illuminate\Support\Facades\DB;
use App\Models\Usuario\usuarioMdl;
use Illuminate\Http\Request;
use App\Models\Documentos\documentosMdl;
use App\Models\Documentos\subtipodocumentoMdl;
use App\Models\Documentos\tipoMdl;
use App\Models\Registro_Documento\registroDocumentoMdl;
use App\Models\Sub_Categoria\subCategoriaMdl;

class ticketCtrl extends Controller
{
    public function getTicketRecibido(Request $request)
    {


        $search = $request->search;
        $id_depto = session('id_departamento');
        $id_usuario = session('id_usuario');
        $recibido = DB::SELECT("SELECT COUNT(erd.id_estado_registro_documento) as item, erd.descripcion as estado, erd.id_estado_registro_documento
                                FROM sci_estado_registro_documento as erd
                                INNER JOIN sci_registro_documento as eo
                                ON erd.id_estado_registro_documento = eo.id_estado_registro
                                WHERE eo.id_departamento_recibe = $id_depto AND eo.id_usuario_solicitante != $id_usuario AND erd.id_estado_registro_documento != 6
                                GROUP BY erd.id_estado_registro_documento, eo.id_estado_registro, erd.descripcion");

        return [
            'recibido' => $recibido
        ];
    }

    public function getFiltroRecibido(Request $request)
    {

        $id_registro = $request->id;
        $search = $request->search;

        $recibido = DB::table('sci_registro_documento as rd')->where('rd.id_departamento_recibe', session('id_departamento'))->Where('rd.id_estado_registro', $id_registro)
            ->where('rd.id_usuario_solicitante', '!=', session('id_usuario'))
            ->join('sci_documentos as d', function ($join) {
                $join->on('rd.id_documento', '=', 'd.id_documentos');
            })
            ->join('sci_usuarios as u', function ($join) {
                $join->on('rd.id_usuario_solicitante', '=', 'u.id_usuario');
            })
            ->join('sci_estrutura_organizacion as eo', function ($join) {
                $join->on('u.id_departamento', '=', 'eo.id_departamento');
            })
            ->join('sci_estado_registro_documento as es', function ($join) {
                $join->on('rd.id_estado_registro', '=', 'es.id_estado_registro_documento');
            })


            ->select(
                "rd.id_registro_documento",
                "rd.id_documento",
                "rd.id_usuario_solicitante",
                "rd.id_departamento_recibe",
                "rd.fechaSolicitud",
                "rd.asunto",
                "rd.descripcion",
                "rd.adjunto",
                "rd.adjunto2",
                "rd.id_estado_registro",
                "d.descripcion as documento",
                "u.usuario",
                "u.nombres",
                "u.apellidos",
                "eo.descripcion as departamento",
                "es.descripcion as estado",
                DB::raw('DATE_FORMAT(rd.fechaSolicitud, "%d/%m/%Y %r") as fecha'),
            )->where('rd.asunto', 'LIKE', '%' . $search . '%')->orderBy('rd.id_registro_documento', 'desc')

            ->paginate(10);

        return [
            'pagination' => [
                'total'         => $recibido->total(),
                'current_page'  => $recibido->currentPage(),
                'per_page'      => $recibido->perPage(),
                'last_page'     => $recibido->lastPage(),
                'from'          => $recibido->firstItem(),
                'to'            => $recibido->lastItem()
            ],
            'recibido' => $recibido
        ];
    }

    public function getDetalleRecibido(Request $request)
    {

        $id_registro = $request->id;
        $search = $request->search;

        $recibido = DB::table('sci_registro_documento as rd')->where('rd.id_departamento_recibe', session('id_departamento'))->Where('rd.id_registro_documento', $id_registro)
            ->where('rd.id_usuario_solicitante', '!=', session('id_usuario'))
            ->join('sci_documentos as d', function ($join) {
                $join->on('rd.id_documento', '=', 'd.id_documentos');
            })
            ->join('sci_usuarios as u', function ($join) {
                $join->on('rd.id_usuario_solicitante', '=', 'u.id_usuario');
            })
            ->join('sci_estrutura_organizacion as eo', function ($join) {
                $join->on('u.id_departamento', '=', 'eo.id_departamento');
            })
            ->join('sci_estado_registro_documento as es', function ($join) {
                $join->on('rd.id_estado_registro', '=', 'es.id_estado_registro_documento');
            })
            ->leftjoin('sci_comentario as co', function ($join) {
                $join->on('rd.id_registro_documento', '=', 'co.id_registro_documento');
            })
            ->leftjoin('sci_categorias as c', function ($join) {
                $join->on('rd.id_registro_documento', '=', 'c.id_registro_documento');
            })
            ->leftjoin('sci_tipos as t', function ($join) {
                $join->on('t.id_tipos', '=', 'c.sci_tipos');
            })
            ->leftjoin('sci_sub_categorias as sc', function ($join) {
                $join->on('sc.id_categorias', '=', 'c.id_categorias');
            })
            ->leftjoin('sci_sub_tipos as st', function ($join) {
                $join->on('st.id_sub_tipos', '=', 'sc.id_sub_tipos');
            })
            ->leftjoin('sci_evaluacion as ev', function ($join) {
                $join->on('rd.id_registro_documento', '=', 'ev.id_registro_documento');
            })
            ->leftjoin('sci_tipo_evaluacion as tev', function ($join) {
                $join->on('ev.id_tipo_evaluacion', '=', 'tev.id_tipo_evaluacion');
            })

            ->select(
                "rd.id_registro_documento",
                "rd.id_documento",
                "rd.id_usuario_solicitante",
                "rd.id_departamento_recibe",
                "rd.asunto",
                "rd.descripcion",
                "rd.adjunto",
                "rd.adjunto2",
                "rd.id_estado_registro",
                "d.descripcion as documento",
                "u.usuario",
                "u.nombres",
                "u.apellidos",
                "eo.descripcion as departamento",
                "es.descripcion as estado",
                "co.comentario",
                "t.descripcion as tipos",
                "st.descripcion as subtipos",
                "ev.comentario as evaluacion",
                "tev.descripcion as tipoEvaluacion",
                DB::raw('DATE_FORMAT(rd.fechaSolicitud, "%d/%m/%Y %r") as fechaSolicitud'),
            )->where('rd.asunto', 'LIKE', '%' . $search . '%')->orderBy('rd.id_registro_documento', 'desc')

            ->paginate(10);

        return [
            'pagination' => [
                'total'         => $recibido->total(),
                'current_page'  => $recibido->currentPage(),
                'per_page'      => $recibido->perPage(),
                'last_page'     => $recibido->lastPage(),
                'from'          => $recibido->firstItem(),
                'to'            => $recibido->lastItem()
            ],
            'detalle' => $recibido
        ];
    }

    public function getAllDocumentos()
    {
        $query = documentosMdl::all();

        return ['query' => $query];
    }

    public function getTicketEnviados(Request $request)
    {

        $search = $request->search;
        $id_depto = session('id_departamento');
        $id_usuario = session('id_usuario');
        $enviados = DB::SELECT("SELECT COUNT(erd.id_estado_registro_documento) as item, erd.descripcion as estado, erd.id_estado_registro_documento
                                FROM sci_estado_registro_documento as erd
                                INNER JOIN sci_registro_documento as eo
                                ON erd.id_estado_registro_documento = eo.id_estado_registro
                                WHERE eo.id_usuario_solicitante = $id_usuario
                                GROUP BY erd.id_estado_registro_documento, eo.id_estado_registro, erd.descripcion");

        return [
            'enviados' => $enviados
        ];
    }

    public function getFiltroEnviados(Request $request)
    {

        $search = $request->search;
        $id_registro = $request->id;
        $enviados = DB::table('sci_registro_documento as rd')->where('rd.id_usuario_solicitante', session('id_usuario'))->Where('rd.id_estado_registro', $id_registro)
            ->join('sci_documentos as d', function ($join) {
                $join->on('rd.id_documento', '=', 'd.id_documentos');
            })
            ->join('sci_usuarios as u', function ($join) {
                $join->on('rd.id_usuario_solicitante', '=', 'u.id_usuario');
            })
            ->join('sci_estrutura_organizacion as eo', function ($join) {
                $join->on('rd.id_departamento_recibe', '=', 'eo.id_departamento');
            })

            ->join('sci_estado_registro_documento as es', function ($join) {
                $join->on('rd.id_estado_registro', '=', 'es.id_estado_registro_documento');
            })

            ->select(
                "rd.id_registro_documento",
                "rd.id_documento",
                "rd.id_usuario_solicitante",
                "rd.id_departamento_recibe",
                "rd.fechaSolicitud",
                "rd.asunto",
                "rd.descripcion",
                "rd.adjunto",
                "rd.adjunto2",
                "rd.id_estado_registro",
                "d.descripcion as documento",
                "u.usuario",
                "es.descripcion as estado",
                "eo.descripcion as departamento",
                DB::raw('DATE_FORMAT(rd.fechaSolicitud, "%d/%m/%Y %r") as fecha'),
            )->where('rd.asunto', 'LIKE', '%' . $search . '%')->orderBy('rd.id_registro_documento', 'desc')

            ->paginate(10);

        return [
            'pagination' => [
                'total'         => $enviados->total(),
                'current_page'  => $enviados->currentPage(),
                'per_page'      => $enviados->perPage(),
                'last_page'     => $enviados->lastPage(),
                'from'          => $enviados->firstItem(),
                'to'            => $enviados->lastItem()
            ],
            'enviados' => $enviados
        ];
    }

    public function getTicketAprobar(Request $request)
    {

        $search = $request->search;
        $id_registro = $request->id;
        $aprobar = DB::table('sci_registro_documento as rd')->where('u.id_departamento', session('id_departamento'))->where('rd.id_estado_registro', 6)
            ->where('rd.id_usuario_solicitante', '!=', session('id_usuario'))
            ->join('sci_usuarios as u', function ($join) {
                $join->on('rd.id_usuario_solicitante', '=', 'u.id_usuario');
            })
            ->join('sci_estrutura_organizacion as eo', function ($join) {
                $join->on('eo.id_departamento', '=', 'u.id_departamento');
            })
            ->join('sci_estado_registro_documento as erd', function ($join) {
                $join->on('erd.id_estado_registro_documento', '=', 'rd.id_estado_registro');
            })
            ->join('sci_documentos as d', function ($join) {
                $join->on('rd.id_documento', '=', 'd.id_documentos');
            })
            ->select(
                "rd.id_registro_documento",
                "rd.id_documento",
                "rd.id_usuario_solicitante",
                "rd.id_departamento_recibe",
                "rd.fechaSolicitud",
                "rd.asunto",
                "rd.descripcion",
                "rd.adjunto",
                "rd.adjunto2",
                "rd.id_estado_registro",
                "u.nombres",
                "u.apellidos",
                "eo.descripcion as departamento",
                "erd.descripcion as estado",
                "d.descripcion as documento",
                DB::raw('DATE_FORMAT(rd.fechaSolicitud, "%d/%m/%Y %r") as fecha'),
            )->where('rd.asunto', 'LIKE', '%' . $search . '%')->orderBy('rd.id_registro_documento', 'desc')

            ->paginate(10);

        return [
            'pagination' => [
                'total'         => $aprobar->total(),
                'current_page'  => $aprobar->currentPage(),
                'per_page'      => $aprobar->perPage(),
                'last_page'     => $aprobar->lastPage(),
                'from'          => $aprobar->firstItem(),
                'to'            => $aprobar->lastItem()
            ],
            'aprobar' => $aprobar
        ];
    }

    public function getDetalleAprobar(Request $request)
    {

        $id_registro = $request->id;
        $search = $request->search;

        $aprobar = DB::table('sci_registro_documento as rd')->where('u.id_departamento', session('id_departamento'))->where('rd.id_estado_registro', 6)
            ->where('rd.id_usuario_solicitante', '!=', session('id_usuario'))
            ->join('sci_documentos as d', function ($join) {
                $join->on('rd.id_documento', '=', 'd.id_documentos');
            })
            ->join('sci_usuarios as u', function ($join) {
                $join->on('rd.id_usuario_solicitante', '=', 'u.id_usuario');
            })
            ->join('sci_estrutura_organizacion as eo', function ($join) {
                $join->on('u.id_departamento', '=', 'eo.id_departamento');
            })
            ->join('sci_estado_registro_documento as es', function ($join) {
                $join->on('rd.id_estado_registro', '=', 'es.id_estado_registro_documento');
            })
            ->leftjoin('sci_comentario as co', function ($join) {
                $join->on('rd.id_registro_documento', '=', 'co.id_registro_documento');
            })
            ->leftjoin('sci_categorias as c', function ($join) {
                $join->on('rd.id_registro_documento', '=', 'c.id_registro_documento');
            })
            ->leftjoin('sci_tipos as t', function ($join) {
                $join->on('t.id_tipos', '=', 'c.sci_tipos');
            })
            ->leftjoin('sci_sub_categorias as sc', function ($join) {
                $join->on('sc.id_categorias', '=', 'c.id_categorias');
            })
            ->leftjoin('sci_sub_tipos as st', function ($join) {
                $join->on('st.id_sub_tipos', '=', 'sc.id_sub_tipos');
            })
            ->select(
                "rd.id_registro_documento",
                "rd.id_documento",
                "rd.id_usuario_solicitante",
                "rd.id_departamento_recibe",
                "rd.asunto",
                "rd.descripcion",
                "rd.adjunto",
                "rd.adjunto2",
                "rd.id_estado_registro",
                "d.descripcion as documento",
                "u.usuario",
                "u.nombres",
                "u.apellidos",
                "eo.descripcion as departamento",
                "es.descripcion as estado",
                "co.comentario",
                "t.descripcion as tipos",
                "st.descripcion as subtipos",
                DB::raw('DATE_FORMAT(rd.fechaSolicitud, "%d/%m/%Y %r") as fechaSolicitud'),
            )->where('rd.asunto', 'LIKE', '%' . $search . '%')->orderBy('rd.id_registro_documento', 'desc')

            ->paginate(10);

        return [
            'pagination' => [
                'total'         => $aprobar->total(),
                'current_page'  => $aprobar->currentPage(),
                'per_page'      => $aprobar->perPage(),
                'last_page'     => $aprobar->lastPage(),
                'from'          => $aprobar->firstItem(),
                'to'            => $aprobar->lastItem()
            ],
            'detalle' => $aprobar
        ];
    }
    public function getDetalleEnviados(Request $request)
    {

        $search = $request->search;
        $id_registro = $request->id;
        $enviados = DB::table('sci_registro_documento as rd')->where('rd.id_usuario_solicitante', session('id_usuario'))->Where('rd.id_registro_documento', $id_registro)
            ->join('sci_documentos as d', function ($join) {
                $join->on('rd.id_documento', '=', 'd.id_documentos');
            })
            ->join('sci_usuarios as u', function ($join) {
                $join->on('rd.id_usuario_solicitante', '=', 'u.id_usuario');
            })
            ->join('sci_estrutura_organizacion as eo', function ($join) {
                $join->on('rd.id_departamento_recibe', '=', 'eo.id_departamento');
            })
            ->join('sci_estado_registro_documento as es', function ($join) {
                $join->on('rd.id_estado_registro', '=', 'es.id_estado_registro_documento');
            })
            ->leftjoin('sci_comentario as co', function ($join) {
                $join->on('rd.id_registro_documento', '=', 'co.id_registro_documento');
            })
            ->leftjoin('sci_categorias as c', function ($join) {
                $join->on('rd.id_registro_documento', '=', 'c.id_registro_documento');
            })
            ->leftjoin('sci_tipos as t', function ($join) {
                $join->on('t.id_tipos', '=', 'c.sci_tipos');
            })
            ->leftjoin('sci_sub_categorias as sc', function ($join) {
                $join->on('sc.id_categorias', '=', 'c.id_categorias');
            })
            ->leftjoin('sci_sub_tipos as st', function ($join) {
                $join->on('st.id_sub_tipos', '=', 'sc.id_sub_tipos');
            })
            ->leftjoin('sci_evaluacion as ev', function ($join) {
                $join->on('rd.id_registro_documento', '=', 'ev.id_registro_documento');
            })
            ->leftjoin('sci_tipo_evaluacion as tev', function ($join) {
                $join->on('ev.id_tipo_evaluacion', '=', 'tev.id_tipo_evaluacion');
            })

            ->select(
                "rd.id_registro_documento",
                "rd.id_documento",
                "rd.id_usuario_solicitante",
                "rd.id_departamento_recibe",
                "rd.asunto",
                "rd.descripcion",
                "rd.adjunto",
                "rd.adjunto2",
                "rd.id_estado_registro",
                "d.descripcion as documento",
                "u.usuario",
                "es.descripcion as estado",
                "eo.descripcion as departamento",
                "co.comentario",
                "t.descripcion as tipos",
                "st.descripcion as subtipos",
                "u.nombres",
                "u.apellidos",
                "ev.comentario as evaluacion",
                "tev.descripcion as tipoEvaluacion",
                DB::raw('DATE_FORMAT(rd.fechaSolicitud, "%d/%m/%Y %r") as fechaSolicitud'),
            )->where('rd.asunto', 'LIKE', '%' . $search . '%')->orderBy('rd.id_registro_documento', 'desc')

            ->paginate(10);

        return [
            'pagination' => [
                'total'         => $enviados->total(),
                'current_page'  => $enviados->currentPage(),
                'per_page'      => $enviados->perPage(),
                'last_page'     => $enviados->lastPage(),
                'from'          => $enviados->firstItem(),
                'to'            => $enviados->lastItem()
            ],
            'detalle' => $enviados
        ];
    }

    public function getTipoDocumento(Request $request)
    {
        $query = tipoMdl::where('id_documento', $request->id_documento)->get();
        return ['query' => $query];
    }

    public function getSubTipoDocumento(Request $request)
    {
        $query = subtipodocumentoMdl::where('id_tipo', $request->id_tipo)->get();
        return ['query' => $query];
    }

    public function getTicketDepto()
    {
        if (session('id_rol') == 6 || session('id_rol') == 1) { //rol 1 es por pruebas
            $departamentos = departamentosMdl::where('id_departamento', '!=', session('id_departamento'))->where('id_estado', 1)->get();
        }
        if (session('id_rol') == 2) {
            $departamentos = departamentosMdl::whereIn('id_tipo_estructura', [1, 3])->where('id_departamento', '!=', session('id_departamento'))
                ->where('id_estado', 1)->get();
        }

        if (session('id_rol') == 3 || session('id_rol') == 5) {
            $departamentos = departamentosMdl::where('id_tipo_estructura', 1)->where('id_departamento', '!=', session('id_departamento'))
                ->where('id_estado', 1)->get();
        }

        return ['departamentos' => $departamentos];
    }

    public function postTicket(Request $request)
    {
        $data = json_decode($request->selectDepto);

        try {
            $path2 = "";
            $path = "";
            $categoria = "";
            $id_estado_registro = "";
            if ($request->file2 != []) {
                $file_name = $request->file2->getClientOriginalName();
                $request->file2->move(public_path('/file'), $file_name);
                $path2 = '/file/' . $file_name;
            }
            if ($request->file != []) {
                $file_name = $request->file->getClientOriginalName();
                $request->file->move(public_path('/file'), $file_name);
                $path = '/file/' . $file_name;
            }

            $request->evaluacion == 0 ? $id_estado_registro = 7 : $id_estado_registro = 1;

            if (
                session('id_rol') == 5 && $request->autoTipo == 1 || session('id_rol') == 5 && $request->autoSubTipo == 1 ||
                session('id_rol') == 5 && $request->autoDoc == 1
            ) {
                $id_estado_registro = 6;
            }
            foreach ($data as $d) {
                $data = [
                    'id_documento' => $request->id_documento, 'id_usuario_solicitante' => session('id_usuario'),
                    'id_departamento_recibe' => $d->id_departamento, 'asunto' => $request->asunto,
                    'descripcion' => $request->descripcion, 'adjunto' => $path, 'adjunto2' => $path2,
                    'id_estado_registro' => $id_estado_registro
                ];


                $registro = registroDocumentoMdl::create($data)->latest('id_registro_documento')->first();
                helperAuth::crearLogs($registro->id_registro_documento, 'sci_registro_documento');

                if ($request->tipos == 1) {
                    $data = ['id_registro_documento' => $registro->id_registro_documento, 'sci_tipos' => $request->selectTipo];
                    $categoria = categoriaMdl::create($data)->latest('id_categorias')->first();
                    helperAuth::crearLogs($categoria->id_categorias, 'sci_categorias');
                }

                if ($request->sub_tipos == 1) {
                    $data = ['id_categorias' => $categoria->id_categorias, 'id_sub_tipos' => $request->selectSubTipo];
                    $sub_categoria = subCategoriaMdl::create($data);
                    helperAuth::crearLogs($sub_categoria->id_sub_categorias, 'sci_sub_categorias');
                }
            }
            return helperAuth::jsonResponse(true, "Datos almacenados correctamente");
        } catch (\Throwable $th) {
            return helperAuth::jsonResponse(false, "Hubo un problema al guardar los datos");
        }
    }
    public function aprobarTicket(Request $request)
    {

        $id = $request->id_registro_documento;
        $estado = $request->accion;
        $id_documento = $request->id_documento;
        $documento = documentosMdl::find($id_documento);
        if ($documento->evaluacion == 0) {
            $estado = 7;
        };
        try {
            $registro = registroDocumentoMdl::find($id);
            $registro->id_estado_registro = $estado;
            $registro->update();

            helperAuth::actualizarLogs($id, 'sci_registro_documento');

            return helperAuth::jsonResponse(true, "El documento ha sido aprobado");
        } catch (\Throwable $th) {
            return helperAuth::jsonResponse(false, "Hubo un problema al guardar los datos");
        }
    }
    public function completarTickets(Request $request)
    {

        $id = $request->id;
        try {
            $registro = registroDocumentoMdl::find($id);
            $registro->id_estado_registro = 3; // estado completado;
            $registro->update();
            helperAuth::actualizarLogs($id, 'sci_registro_documento');

            return helperAuth::jsonResponse(true, "El documento ha sido completado");
        } catch (\Throwable $th) {
            return helperAuth::jsonResponse(false, "Hubo un problema al guardar los datos");
        }
    }

    public function denegarTickets(Request $request)
    {

        $id = $request->id_registro_documento;
        try {
            $registro = registroDocumentoMdl::find($id);
            $registro->id_estado_registro = 8; // estado completado;
            $registro->update();
            helperAuth::actualizarLogs($id, 'sci_registro_documento');

            return helperAuth::jsonResponse(true, "El documento ha sido denegado");
        } catch (\Throwable $th) {
            return helperAuth::jsonResponse(false, $th);
        }
    }
}
