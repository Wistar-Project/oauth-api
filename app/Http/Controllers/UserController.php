<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function ValidateToken(){
        return auth('api') -> user();
    }

    public function Logout(Request $request){
        $request -> user() -> token() -> revoke();
        return ['message' => 'Sesión cerrada satisfactoriamente'];
    }

    public function MostrarPersona(){
        return auth('api') -> user() -> persona;
    }
}
