<?php

namespace App\Http\Controllers\login;

use App\Http\Controllers\Controller;
use App\Http\Controllers\helperAuth;
use App\Models\Usuario\usuarioMdl;
use Illuminate\Http\Request;

class loginCtrl extends Controller
{
    //
    public function loginVW()
    {

        return view('login');
    }

    public function verificLogin(Request $request)
    {

        try {
            $credentials = $request->validate([
                'usuario' => ['required'],
                'password' => ['required'],
            ]);

            $correo = $request->usuario;
            $password = $request->password;
            $usuario = usuarioMdl::select('id_usuario', 'id_departamento', 'usuario', 'contraseña', 'nombres', 'apellidos', 'id_rol', 'id_estado')
                ->where('usuario', $correo)
                ->first();

            if ($usuario->id_estado == 2) {
                return helperAuth::jsonResponse(false, "El usuario se encuentra inactivo");
            }

            if ($usuario->id_estado == 3) {
                return helperAuth::jsonResponse(false, "El usuario se encuentra inactivo");
            }

            if (!helperAuth::modelHasRows($usuario)) {
                return helperAuth::jsonResponse(false, "Credenciales invalidas");
            }


            if (!password_verify($password, $usuario->contraseña)) {
                return helperAuth::jsonResponse(false, "Credenciales invalidas");
            }

            session(['id_usuario' => $usuario->id_usuario]);
            session(['id_rol' => $usuario->id_rol]);
            session(['id_departamento' => $usuario->id_departamento]);
            helperAuth::loginLogs($usuario->id_usuario, 'sci_usuario');

            return helperAuth::jsonResponse(true, "Credenciales validas redireccionando.....");
        } catch (\Throwable $th) {
            return helperAuth::jsonResponse(false, "Hubo un problema al consultar los datos");
        }
    }
    public function salir()
    {
        session()->flush();
        return redirect('/');
    }
}
