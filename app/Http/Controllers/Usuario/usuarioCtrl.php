<?php

namespace App\Http\Controllers\Usuario;

use App\Http\Controllers\Controller;
use App\Http\Controllers\helperAuth;
use App\Mail\passReset;
use App\Models\Usuario\usuarioMdl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class usuarioCtrl extends Controller
{
    public function getUsuario(Request $request)
    {
        $search = $request->search;

        $usuario = DB::table('sci_usuarios as u')
            ->join('sci_rol as r', function ($join) {
                $join->on('u.id_rol', '=', 'r.id_rol');
            })->join('sci_estrutura_organizacion as eo', function ($join) {
                $join->on('eo.id_departamento', '=', 'u.id_departamento');
            })->join('sci_estado as e', function ($join) {
                $join->on('e.id_estado', '=', 'u.id_estado');
            })
            ->select(
                'u.id_usuario',
                'u.nombres',
                'u.apellidos',
                'u.correo',
                'u.usuario',
                'r.descripcion as rol',
                'eo.descripcion as departamento',
                'e.descripcion as estado',
                'u.id_estado',
                'u.id_departamento',
                'u.id_rol'
            )->where('u.nombres', 'LIKE', '%' . $search . '%')->orderBy('u.id_usuario', 'desc')
            ->orWhere('u.apellidos', 'LIKE', '%' . $search . '%')->orderBy('u.nombres', 'desc')
            ->paginate(10);
        return [
            'pagination' => [
                'total'         => $usuario->total(),
                'current_page'  => $usuario->currentPage(),
                'per_page'      => $usuario->perPage(),
                'last_page'     => $usuario->lastPage(),
                'from'          => $usuario->firstItem(),
                'to'            => $usuario->lastItem()
            ],
            'usuarios' => $usuario
        ];
    }

    public function postUsuario(Request $request)
    {
        try {
            $randomPass = helperAuth::randomString(8);
            $password = password_hash($randomPass, PASSWORD_DEFAULT);
            $nombres = $request->nombres;
            $apellidos = $request->apellidos;
            $email = $request->email;
            $selectEstado = $request->selectEstado;
            $selectRol = $request->selectRol;
            $selectDepto = $request->selectDepto;
            $usuario = explode("@", $email);

            $data = [
                'nombres' => $nombres, 'apellidos' => $apellidos, 'correo' => $email,
                'contraseña' => $password, 'id_estado' => $selectEstado, 'id_rol' => $selectRol,
                'id_departamento' => $selectDepto, "usuario" => $usuario[0]
            ];


            $usuarios = usuarioMdl::where('correo', $email)->first();


            if (helperAuth::modelHasRows($usuarios)) {
                return helperAuth::jsonResponse(false, "El usuario ya existe");
            }

            $user = usuarioMdl::create($data);
            helperAuth::crearLogs($user->id_usuario, 'sci_usuarios');

            $data = array(
                'nombre'   => $nombres . ' ' . $apellidos,
                'view'      => "correoContrasena", // Se establece estatica la vista que tiene la estructura para el cliente
                'usuario' => $usuario[0],
                'contraseña' => $randomPass,
                'accion' => "Hemos creado tu cuenta en el Sistema de CONTROL DE GESTIÓN MUNICIPAL"
            ); // Creamos un arreglos con los datos recibidos desde el formulario de contacto a traves de axios, javascript
            if ($request->selectRol != 4) {
                Mail::to($email)->send(new passReset($data)); // Se hace en envio del correo al cliente que realizo el formulario
            }

            return helperAuth::jsonResponse(true, "Datos almacenados correctamente");
        } catch (\Throwable $th) {
            return helperAuth::jsonResponse(false, "Hubo un problema al guardar los datos");
        }
    }

    public function putUsuario(Request $request)
    {

        try {

            $nombres = $request->nombres;
            $apellidos = $request->apellidos;
            $email = $request->email;
            $selectEstado = $request->selectEstado;
            $selectRol = $request->selectRol;
            $selectDepto = $request->selectDepto;
            $id_usuario = $request->id_usuario;
            $usuario = explode("@", $email);

            $usuarios = usuarioMdl::where('id_usuario', $id_usuario)->get();

            $data = [
                'nombres' => $nombres, 'apellidos' => $apellidos, 'correo' => $email,
                'id_estado' => $selectEstado, 'id_rol' => $selectRol,
                'id_departamento' => $selectDepto, "usuario" => $usuario[0]
            ];

            if ($usuarios['0']->id_rol != $selectRol) {
                $valid = usuarioMdl::where('correo', $email)->where('id_rol', $selectRol)->first();

                if (helperAuth::modelHasRows($valid)) {
                    return helperAuth::jsonResponse(false, "El usuario ya existe");
                }
            }

            usuarioMdl::updateOrCreate(['id_usuario' => $id_usuario], $data);
            helperAuth::actualizarLogs($id_usuario, 'sci_usuarios');

            return helperAuth::jsonResponse(true, "Datos almacenados correctamente");
        } catch (\Throwable $th) {
            return helperAuth::jsonResponse(false, "Hubo un problema al guardar los datos");
        }
    }
    public function eliminarUsuario(Request $request)
    {

        $id = $request->id_usuario;
        $estado = $request->accion;

        try {
            $usuario = usuarioMdl::find($id);
            $usuario->id_estado = $estado;
            $usuario->update();
            helperAuth::eliminarLogs($id, 'sci_usuarios');

            return helperAuth::jsonResponse(true, "El usuario ha sido modificado");
        } catch (\Throwable $th) {
            return helperAuth::jsonResponse(false, "Hubo un problema al guardar los datos");
        }
    }
    public function reinicioContrasena(Request $request)
    {

        $id = $request->id_usuario;
        $randomPass = helperAuth::randomString(8);
        $password = password_hash($randomPass, PASSWORD_DEFAULT);
        $nombres = $request->nombres;
        $apellidos = $request->apellidos;
        $email = $request->correo;
        $usuario = $request->usuario;

        try {
            $usuarios = usuarioMdl::find($id);
            $usuarios->contraseña = $password;
            $usuarios->update();
            helperAuth::actualizarLogs($id, 'sci_usuarios');

            $data = array(
                'nombre'   => $nombres . ' ' . $apellidos,
                'view'      => "correoContrasena", // Se establece estatica la vista que tiene la estructura para el cliente
                'usuario' => $usuario,
                'contraseña' => $randomPass,
                'accion' => "Hemos reiniciado tu contraseña en el Sistema de CONTROL DE GESTIÓN MUNICIPAL"
            ); // Creamos un arreglos con los datos recibidos desde el formulario de contacto a traves de axios, javascript

            Mail::to($email)->send(new passReset($data)); // Se hace en envio del correo al cliente que realizo el formulario

            return helperAuth::jsonResponse(true, "El usuario ha sido modificado");
        } catch (\Throwable $th) {
            return helperAuth::jsonResponse(false, "Hubo un problema al guardar los datos");
        }
    }
    public function getPerfil()
    {
        $id = session('id_usuario');
        $perfil = DB::SELECT("SELECT u.nombres, u.apellidos, u.correo, eo.descripcion as departamento
                              FROM sci_usuarios as u
                              INNER JOIN sci_estrutura_organizacion as eo
                              ON u.id_departamento = eo.id_departamento
                              WHERE u.id_usuario = $id");
        return ['perfil' => $perfil];
    }

    public function perfilUpdate(Request $request)
    {
        try {

            $nombres = $request->nombres;
            $apellidos = $request->apellidos;
            $contraseña = $request->contraseña;
            $password = password_hash($contraseña, PASSWORD_DEFAULT);
            $data = [
                'nombres' => $nombres, 'apellidos' => $apellidos, "contraseña" => $password
            ];

            usuarioMdl::updateOrCreate(['id_usuario' => session('id_usuario')], $data);
            helperAuth::actualizarLogs(session('id_usuario'), 'sci_usuarios');

            return helperAuth::jsonResponse(true, "Datos almacenados correctamente");
        } catch (\Throwable $th) {
            return helperAuth::jsonResponse(false, "Hubo un problema al guardar los datos");
        }
    }
}
