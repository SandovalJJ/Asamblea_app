<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SessionsController extends Controller
{
    public function login()
    {
        return view("login");
    }

    public function login_post(Request $request)
    {
        if (auth()->attempt(request(['email', 'password'])) == false) {
            return back()->withErrors([
                'message' => 'El usuario o la contraseña es incorrecto!'
            ]);
        }
        

        $user = auth()->user();
    
        if ($user->activo == 0) {
            auth()->logout();
    
            return back()->withErrors([
                'message' => 'Tu cuenta está desactivada. Por favor, contacta al administrador.'
            ]);
        }
        return redirect()->to('inicia-sesion');
    }

}

