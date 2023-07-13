<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Lcobucci\JWT\Parser;
use Illuminate\Support\Facades\Validator;
use App\Models\Persona;
use App\Models\Administrador;
use App\Models\Conductor;
use App\Models\Funcionario; 
use Illuminate\Support\Facades\DB;




class UserController extends Controller
{
    public function Register(Request $request){

        $validation = Validator::make($request->all(),[
            'email' => 'required|email|unique:users',
            'rol'=> 'required',
            'nombre'=>'required|max:50',
            'apellido'=>'required|max:50',
            'password' => 'required|confirmed'
        ]);

        if($validation->fails())
            return $validation->errors();

        DB::transaction(function() use($request){
            $usuario= $this -> createUser($request);
            $this ->createPersona($request,$usuario->id);
            $this ->addRoleToPersona($usuario ->id,$request->rol);
        });
       
        
    }

    private function createUser($request){
        $user = new User();
        $user -> email = $request -> post("email");
        $user -> password = Hash::make($request -> post("password"));   
        $user -> save();
        return $user;
    }

    public function ValidateToken(Request $request){
        return auth('api')->user();
    }

    public function Logout(Request $request){
        $request->user()->token()->revoke();
        return ['message' => 'Token Revoked'];
        
        
    }

    private function createPersona($request ,$id){
        $persona = new Persona();
        $persona -> nombre= $request ->post('nombre');
        $persona -> apellido= $request ->post('apellido');
        $persona -> id= $id;
        $persona -> save();

        return $persona;
    }
    
    private function createConductor($id){
        $conductor = new Conductor();
        $conductor -> id= $id;
        $conductor-> save();

        return $conductor;
    }
    
    private function createAdministrador($id){
        $administrador = new Administrador();
        $administrador -> id= $id;
        $administrador-> save();

        return $administrador;
    }
  
    private function createFuncionario($id){
        $funcionario = new Funcionario();
        $funcionario -> id= $id;
        $funcionario-> save();

        return $funcionario;
    }
    private function addRoleToPersona($id,$rol){
        $BAD_REQUEST_HTTP=400;
        if($rol == 'administrador'){
            $this -> createAdministrador($id);
            return;
        }
        if($rol == 'conductor'){
            $this -> createConductor($id);
            return;
        }
        if($rol == 'funcionario'){
            $this -> createFuncionario($id);
            return;
        }
        abort($BAD_REQUEST_HTTP,"El rol especificado no es valido");
    }
}
