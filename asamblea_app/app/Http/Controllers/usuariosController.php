<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\DataTables;

class usuariosController extends Controller
{
    public function index(Request $request){ {
        if($request->ajax()){
            $usuarios = User::all();
            return DataTables::of($usuarios)
                ->addColumn('action',function($usuarios){
                    $acciones = '<button type="button" class="btn btn-dark btn-sm edit" data-id="' . $usuarios->id . '">Editar</button>';
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
        $usuario->name = $request->nombreU;
        $usuario->email = $request->email;
        $usuario->cedula = $request->cedula;
        $usuario->agencia = $request->agencia;
        $usuario->cuenta = $request->cuenta;
        $usuario->telefono = $request->telefono;
        $usuario->rol = $request->rol;
        $password = Hash::make($request->cedula);
        $usuario->password = $password;
        $usuario->save();
        return response()->json(['success' => 'Usuario creado correctamente.']);
    }

        public function editar(Request $request, $id)
    {
        $usuario = User::findOrFail($id);
        $usuario->name = $request->nombreU;
        $usuario->email = $request->email;
        $usuario->cedula = $request->cedula;
        $usuario->agencia = $request->agencia;
        $usuario->cuenta = $request->cuenta;
        $usuario->telefono = $request->telefono;
        $usuario->rol = $request->rol;

        $usuario->save();

        return response()->json(['success' => 'Usuario actualizado correctamente.']);
    }

    public function eliminar($id) {
        $usuario = User::findOrFail($id);
        $usuario->delete();
    
        return response()->json(['success' => 'Usuario eliminado correctamente.']);
    }
    
}
