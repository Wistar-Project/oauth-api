<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Persona;
use App\Models\Funcionario;
use App\Models\Conductor;
use App\Models\Administrador;
use App\Models\Gerente;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $usuarioGerente = User::factory() -> create([
            "email" => "gerente@gotruck.com",
            "password" => Hash::make("1234")
        ]);
        Persona::factory() -> create([
            "id" => $usuarioGerente -> id,
            "nombre" => "Gabriel",
            "apellido" => "Pereira"
        ]);
        $gerente = new Gerente();
        $gerente -> id = $usuarioGerente -> id;
        $gerente -> save();
    }
}
