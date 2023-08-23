<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function Logout(Request $request){
        $request -> user() -> token() -> revoke();
        return ['message' => 'Sesión cerrada satisfactoriamente'];
    }

    public function NombreCompleto(){
        $persona = auth('api') -> user() -> persona;
        $nombreCompleto = $persona -> nombre . " " . $persona -> apellido;
        return $nombreCompleto;
    }
}
