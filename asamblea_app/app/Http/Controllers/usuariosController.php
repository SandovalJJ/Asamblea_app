<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class usuariosController extends Controller
{
    public function index(Request $request){ {
        if($request->ajax()){
            $usuarios = User::all();
            return DataTables::of($usuarios)
                ->addColumn('action',function($usuarios){
                    $acciones = '<a href="" class="btn btn-dark btn-sm"> Editar</a>';
                    $acciones .= '&nbsp;&nbsp;<button type="button" name="delete" id="'.$usuarios->id.'" class=" delete btn btn-primary btn-sm">Borrar</button>';
                    return $acciones;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }
        return view('usuarios');
    }

    public function registrar(Request $request){
        $usuario = new User;
        $usuario->name = $request->nombreU; // Asumiendo que 'nombre' es la columna en tu tabla.
        $usuario->email = $request->email; // Y que 'email' también es una columna.
    
        // Guardar el nuevo usuario en la base de datos.
        $usuario->save();
    
        // Redirigir al usuario de vuelta a la página anterior.
        return response()->json(['success' => 'Usuario creado correctamente.']);

    }

    public function eliminar($id) {
        $usuario = User::findOrFail($id);
        $usuario->delete();
    
        return response()->json(['success' => 'Usuario eliminado correctamente.']);
    }
    
}
