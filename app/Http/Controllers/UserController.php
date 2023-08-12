<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Persona;
use App\Models\Administrador;
use App\Models\Conductor;
use App\Models\Funcionario; 
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    private function validarDatos($request){
        return Validator::make($request->all(),[
            'email' => 'required|email|unique:users',
            'rol'=> 'required|in:administrador,funcionario,conductor',
            'nombre'=>'required|max:50',
            'apellido'=>'required|max:50',
            'password' => 'required|confirmed'
        ]);
    }

    public function Register(Request $request){
        $validacion = $this -> validarDatos($request); 
        if($validacion -> fails()){
            $BAD_REQUEST_HTTP=400;
            abort($BAD_REQUEST_HTTP, $validacion->errors());
        }
        DB::transaction(function() use($request){
            $usuario = $this -> createUser($request);
            $this -> createPersona($request,$usuario->id);
            $this -> addRoleToPersona($usuario ->id,$request->rol);
        });
    }

    private function createUser($request){
        $user = new User();
        $user -> email = $request -> post("email");
        $user -> password = Hash::make($request -> post("password"));   
        $user -> save();
        return $user;
    }

    public function ValidateToken(){
        return auth('api')->user();
    }

    public function Logout(Request $request){
        $request -> user() -> token() -> revoke();
        return ['message' => 'Token Revoked'];
    }

    private function createPersona($request ,$id){
        $persona = new Persona();
        $persona -> nombre = $request -> post('nombre');
        $persona -> apellido = $request -> post('apellido');
        $persona -> id = $id;
        $persona -> save();
    }
    
    private function createConductor($id){
        $conductor = new Conductor();
        $conductor -> id = $id;
        $conductor-> save();
    }
    
    private function createAdministrador($id){
        $administrador = new Administrador();
        $administrador -> id = $id;
        $administrador-> save();
    }
  
    private function createFuncionario($id){
        $funcionario = new Funcionario();
        $funcionario -> id = $id;
        $funcionario-> save();
    }
    private function addRoleToPersona($id,$rol){
        if($rol == 'administrador')
            return $this -> createAdministrador($id);
        if($rol == 'conductor')
            return $this -> createConductor($id);
        return $this -> createFuncionario($id);
    }

    public function MostrarPersona(){
        $user = auth('api') -> user();
        return Persona::find($user -> id);
    }
}
