<?php

use App\Http\Controllers\FormController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\usuariosController;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('login');
});

Route::get('/usuarios', function () {
    return view('usuarios');
});

Route::get('/admin', function () {
    return view('admin');
});

Route::post('/',[UserController::class, 'login2'])->name('iniciar');
Route::post('/logout',[UserController::class, 'logout'])->name('cerrar');


Route::get('usuario',[usuariosController::class, 'index'])->name('usuario.index');
Route::post('usuario',[usuariosController::class, 'registrar'])->name('usuario.registrar');
Route::get('usuario/eliminar/{id}',[usuariosController::class, 'eliminar'])->name('usuario.eliminar');


Route::get('/formularios', [FormController::class, 'create'])->name('form.create');
Route::post('/formularios', [FormController::class, 'store'])->name('forms.store');


Route::get('/show_formulario', [FormController::class, 'show'])->name('formularios.index');
Route::get('/asamblea', [FormController::class, 'showLatestForm'])->name('form.latest');
Route::post('/form/answer/{formId}/{userId}', [FormController::class, 'saveResponse'])->name('form.save-response');


Route::get('/respuestas/{formId}', [FormController::class, 'mostrarRespuestas']);


Route::get('/generar-grafico', [FormController::class, 'generarGrafico'])->name('grafico.generar');