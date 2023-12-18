<?php

use App\Http\Controllers\FormController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\usuariosController;
use Illuminate\Support\Facades\Route;

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

//RUTAS PARA LA GESTIÓN DE USUARIOS
Route::get('usuario',[usuariosController::class, 'index'])->name('usuario.index');
Route::post('usuario',[usuariosController::class, 'registrar'])->name('usuario.registrar');
Route::post('/usuario-editar-{id}', [usuariosController::class,'editar'])->name('usuario.editar');
Route::get('usuario-eliminar-{id}',[usuariosController::class, 'eliminar'])->name('usuario.eliminar');

//RUTAS PARA LA GESTIÓN DE FORMULARIOS
Route::get('/formularios', [FormController::class, 'create'])->name('form.create');
Route::post('/formularios', [FormController::class, 'store'])->name('forms.store');
Route::get('/show_formulario', [FormController::class, 'show'])->name('formularios.index');

//pruebuki asamblea campo por campo
Route::get('/asamblea-{formId}-{fieldIndex}', [FormController::class, 'showFieldByIndex'])->name('form.show-field');

//RUTAS PARA LA GESTION DE RESPUESTAS
Route::post('/forms-{formId}-response', [FormController::class, 'saveResponse'])->name('form.save-response');

Route::get('/respuestas-{formId}', [FormController::class, 'mostrarRespuestas']);

//GRAFICO
Route::get('/generar-grafico', [FormController::class, 'generarGrafico'])->name('grafico.generar');

Route::get('/generar-pdf-{formId}', [FormController::class, 'generarPDF'])->name('generar.pdf');

Route::get('/form-field-toggle-active-{fieldId}', [FormController::class, 'toggleActive'])->name('form-field.toggle-active');

Route::post('/assign-users-{formularioId}', [FormController::class,'assignUsers'])->name('assign-users');

Route::post('/unassign-users-{formularioId}', [FormController::class,'unassignUsers'])->name('unassign-users');

Route::get('/formularios-{formId}', [FormController::class,'generarPDF'])->name('formularios.pdf');


