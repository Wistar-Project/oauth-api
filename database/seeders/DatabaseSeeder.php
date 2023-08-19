<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Persona;
use App\Models\Funcionario;
use App\Models\Conductor;
use App\Models\Administrador;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $usuarioTesting = User::factory() -> create([
            "email" => "usuario@usuario",
            "password" => Hash::make("1234")
        ]);
        Persona::factory() -> create([
            "id" => $usuarioTesting -> id,
            "nombre" => "pepe",
            "apellido" => "hola"
        ]);
        Administrador::factory() -> create([
            "id" => $usuarioTesting -> id
        ]);

        $usuarios = User::factory(30) -> create();
        for($i = 0; $i < count($usuarios); $i++){
            Persona::factory() -> create([
                "id" => $usuarios[$i] -> id
            ]);
            if($i <= 9 ){
                Funcionario::factory() -> create([
                    "id" => $usuarios[$i] -> id
                ]);
                continue;
            }
            if($i <= 19 && $i >= 10 ){
                Conductor::factory() -> create([
                    "id" => $usuarios[$i] -> id
                ]);
                continue;
             }
            Administrador::factory() -> create([
                "id" => $usuarios[$i] -> id
            ]);
        }
    }

}
