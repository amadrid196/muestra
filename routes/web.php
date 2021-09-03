<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


/* Route::get('/', function () {
    return view('welcome');
}); */

Route::get('/', 'App\Http\Controllers\login\loginCtrl@loginVW');
//login
Route::group(['middleware' => 'Headers'], function () {

    Route::post('/verificLogin', 'App\Http\Controllers\login\loginCtrl@verificLogin');
    Route::get('/salir', 'App\Http\Controllers\login\loginCtrl@salir');

    //Route Ticket
    Route::get('/getAllDocumentos', 'App\Http\Controllers\Ticket\ticketCtrl@getAllDocumentos');
    Route::get('/getTicketRecibido', 'App\Http\Controllers\Ticket\ticketCtrl@getTicketRecibido');
    Route::get('/getFiltroRecibido', 'App\Http\Controllers\Ticket\ticketCtrl@getFiltroRecibido');
    Route::get('/getDetalleRecibido', 'App\Http\Controllers\Ticket\ticketCtrl@getDetalleRecibido');
    Route::get('/getTicketEnviados', 'App\Http\Controllers\Ticket\ticketCtrl@getTicketEnviados');
    Route::get('/getFiltroEnviados', 'App\Http\Controllers\Ticket\ticketCtrl@getFiltroEnviados');
    Route::get('/getTicketAprobar', 'App\Http\Controllers\Ticket\ticketCtrl@getTicketAprobar');
    Route::post('/aprobarTicket', 'App\Http\Controllers\Ticket\ticketCtrl@aprobarTicket');
    Route::get('/getDetalleEnviados', 'App\Http\Controllers\Ticket\ticketCtrl@getDetalleEnviados');
    Route::post('/comentarPost', 'App\Http\Controllers\comentar\comentarCtrl@comentarPost');
    Route::post('/evaluarPost', 'App\Http\Controllers\Evaluar\evaluarCtrl@evaluarPost');
    Route::post('/completarTickets', 'App\Http\Controllers\Ticket\ticketCtrl@completarTickets');
    Route::get('/getDetalleAprobar', 'App\Http\Controllers\Ticket\ticketCtrl@getDetalleAprobar');
    Route::post('/denegarTickets', 'App\Http\Controllers\Ticket\ticketCtrl@denegarTickets');

    Route::get('/getTipoDocumento', 'App\Http\Controllers\Ticket\ticketCtrl@getTipoDocumento');
    Route::get('/getSubTipoDocumento', 'App\Http\Controllers\Ticket\ticketCtrl@getSubTipoDocumento');
    Route::get('/getTicketDepto', 'App\Http\Controllers\Ticket\ticketCtrl@getTicketDepto');
    Route::post('/postTicket', 'App\Http\Controllers\Ticket\ticketCtrl@postTicket');

    //Route Usuario
    Route::put('/putUsuario', 'App\Http\Controllers\Usuario\usuarioCtrl@putUsuario');
    Route::post('/postUsuario', 'App\Http\Controllers\Usuario\usuarioCtrl@postUsuario');
    Route::get('/getUsuario', 'App\Http\Controllers\Usuario\usuarioCtrl@getUsuario');
    Route::get('/getPerfil', 'App\Http\Controllers\Usuario\usuarioCtrl@getPerfil');
    Route::post('/eliminarUsuario', 'App\Http\Controllers\Usuario\usuarioCtrl@eliminarUsuario');
    Route::post('/reinicioContrasena', 'App\Http\Controllers\Usuario\usuarioCtrl@reinicioContrasena');
    Route::put('/perfil/update', 'App\Http\Controllers\Usuario\usuarioCtrl@perfilUpdate');

    //Route::get('/getDocumentos', 'App\Http\Controllers\Menu\menuCrtl@getDocumentos');
    Route::post('/documentoPost', 'App\Http\Controllers\Documento\documentosCtrl@documentoPost');
    Route::get('/getDocumento', 'App\Http\Controllers\Documento\documentosCtrl@getDocumento');
    Route::put('/putDocumento', 'App\Http\Controllers\Documento\documentosCtrl@putDocumento');
    Route::post('/deleteDocumento', 'App\Http\Controllers\Documento\documentosCtrl@deleteDocumento');


    //Route estado
    Route::post('/estadoPost', 'App\Http\Controllers\Estado\estadosCtrl@estadoPost');
    Route::get('/getEstado', 'App\Http\Controllers\Estado\estadosCtrl@getEstado');
    Route::put('/putEstado', 'App\Http\Controllers\Estado\estadosCtrl@putEstado');
    Route::post('/deleteEstado', 'App\Http\Controllers\Estado\estadosCtrl@deleteEstado');

    //Route estado registro
    Route::post('/estadoRegistroPost', 'App\Http\Controllers\Estado_Registro\estadoRegistroCtrl@estadoRegistroPost');
    Route::get('/getEstadoRegistro', 'App\Http\Controllers\Estado_Registro\estadoRegistroCtrl@getEstadoRegistro');
    Route::put('/putEstadoRegistro', 'App\Http\Controllers\Estado_Registro\estadoRegistroCtrl@putEstadoRegistro');
    Route::post('/deleteEstadoRegistro', 'App\Http\Controllers\Estado_Registro\estadoRegistroCtrl@deleteEstadoRegistro');

    //route rol
    Route::post('/rolPost', 'App\Http\Controllers\rol\rolCtrl@rolPost');
    Route::get('/getRol', 'App\Http\Controllers\Rol\rolCtrl@getRol');
    Route::put('/putRol', 'App\Http\Controllers\Rol\rolCtrl@putRol');
    Route::post('/deleteRol', 'App\Http\Controllers\Rol\rolCtrl@deleteRol');

    //route departamento
    Route::post('/departamentoPost', 'App\Http\Controllers\Departamento\departamentosCtrl@departamentoPost');
    Route::get('/getDepartamento', 'App\Http\Controllers\Departamento\departamentosCtrl@getDepartamento');
    Route::put('/putDepartamento', 'App\Http\Controllers\Departamento\departamentosCtrl@putDepartamento');
    Route::post('/deleteDepartamento', 'App\Http\Controllers\Departamento\departamentosCtrl@deleteDepartamento');


    //Route Evaluacion
    Route::post('/tipoEvaluacionPost', 'App\Http\Controllers\Evaluacion\tipoEvaluacionCtrl@tipoEvaluacionPost');
    Route::get('/gettipoEvaluacion', 'App\Http\Controllers\Evaluacion\tipoEvaluacionCtrl@gettipoEvaluacion');
    Route::put('/puttipoEvaluacion', 'App\Http\Controllers\Evaluacion\tipoEvaluacionCtrl@puttipoEvaluacion');
    Route::post('/deletetipoEvaluacion', 'App\Http\Controllers\Evaluacion\tipoEvaluacionCtrl@deletetipoEvaluacion');

    //Route Estructura
    Route::post('/tipoEstructuraPost', 'App\Http\Controllers\Estructura\tipoEstructuraCtrl@tipoEstructuraPost');
    Route::get('/gettipoEstructura', 'App\Http\Controllers\Estructura\tipoEstructuraCtrl@gettipoEstructura');
    Route::put('/puttipoEstructura', 'App\Http\Controllers\Estructura\tipoEstructuraCtrl@puttipoEstructura');
    Route::post('/deletetipoEstructura', 'App\Http\Controllers\Estructura\tipoEstructuraCtrl@deletetipoEstructura');

    //Route Tipo documento
    Route::post('/tipoDocumentoPost', 'App\Http\Controllers\Documento\tipoCtrl@tipoDocumentoPost');
    Route::get('/gettipoDocumento', 'App\Http\Controllers\Documento\tipoCtrl@gettipoDocumento');
    Route::put('/puttipoDocumento', 'App\Http\Controllers\Documento\tipoCtrl@puttipoDocumento');
    Route::post('/deletetipoDocumento', 'App\Http\Controllers\Documento\tipoCtrl@deletetipoDocumento');


    //Route SUB Tipo documento
    Route::post('/subtipoDocumentoPost', 'App\Http\Controllers\Documento\subtipodocumentoCtrl@subtipoDocumentoPost');
    Route::get('/getsubtipoDocumento', 'App\Http\Controllers\Documento\subtipodocumentoCtrl@getsubtipoDocumento');
    Route::put('/putsubtipoDocumento', 'App\Http\Controllers\Documento\subtipodocumentoCtrl@putsubtipoDocumento');
    Route::post('/deletesubtipoDocumento', 'App\Http\Controllers\Documento\subtipodocumentoCtrl@deletesubtipoDocumento');

    Route::group(['middleware' => 'validarLogin'], function () {


        // Route Vuejs
        Route::prefix('/')->group(function () {
            Route::get('/{any}', 'App\Http\Controllers\Menu\menuCrtl@menuUsuarioVw')->where('any', '.*');
        });
    });
});
