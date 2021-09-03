<?php

namespace App\Http\Controllers;

use App\Models\Logs\logsMdl;
use Illuminate\Http\Request;

class helperAuth extends Controller
{
    public static function jsonResponse(bool $success, string $msj, $exception = null)
    {
        return [
            'success' => $success,
            'msj' => $msj,
            'id_rol' => session('id_rol')
        ];
    }

    public static function validValue($object): bool
    {

        if ($object == null || $object == "")
            return false;

        return true;
    }
    public static function loginLogs($id_tabla, $tabla)
    {
        $logs = [
            'accion' => 'login', 'tabla' => $tabla, 'id_usuario' => session('id_usuario'),
            'id_tabla' => $id_tabla
        ];
        logsMdl::create($logs);
    }
    public static function crearLogs($id_tabla, $tabla)
    {
        $logs = [
            'accion' => 'crear', 'tabla' => $tabla, 'id_usuario' => session('id_usuario'),
            'id_tabla' => $id_tabla
        ];
        logsMdl::create($logs);
    }
    public static function actualizarLogs($id_tabla, $tabla)
    {
        $logs = [
            'accion' => 'actualizar', 'tabla' => $tabla, 'id_usuario' => session('id_usuario'),
            'id_tabla' => $id_tabla
        ];
        logsMdl::create($logs);
    }

    public static function eliminarLogs($id_tabla, $tabla)
    {
        $logs = [
            'accion' => 'eliminar', 'tabla' => $tabla, 'id_usuario' => session('id_usuario'),
            'id_tabla' => $id_tabla
        ];
        logsMdl::create($logs);
    }
    public static function modelHasRows($model): bool
    {

        if (!self::validValue($model))
            return false;

        if (!$model->count() > 0)
            return false;

        return true;
    }

    public static function randomString($strLenght = 8)
    {

        try {
            $diccionario = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789_-*+@%#$";
            $str = array();
            $length = strlen($diccionario) - 1;

            for ($i = 0; $i < $strLenght; $i++) {
                $n = random_int(0, $length);
                $str[] = $diccionario[$n];
            }

            return implode($str); //devolver password como string

        } catch (\Throwable $th) {
            //echo $exc->getMessage();
            return false;
        }
    }
}
