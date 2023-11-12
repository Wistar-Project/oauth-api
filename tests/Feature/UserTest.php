<?php

namespace Tests\Feature;

use App\Models\Administrador;
use App\Models\Conductor;
use App\Models\Funcionario;
use App\Models\Persona;
use App\Models\User;
use Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;
use Laravel\Passport\Client;
use Illuminate\Support\Facades\Http;



class UserTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_iniciar_sesion_con_client_valido()
    {
        $usuarioTesting = User::factory() -> create([
            "email" => "persona@email.com",
            "password" => Hash::make("contra1234")
        ]);
        Persona::factory() -> create([
            "id" => $usuarioTesting -> id,
            "nombre" => "Martin",
            "apellido" => "Lopez"
        ]);
        Administrador::factory() -> create([
            "id" => $usuarioTesting -> id
        ]);

        Artisan::call('passport:client',[
            '--password' => true,
            '--no-interaction'=>true,
            '--name'=>'Test Client',
        ]);        
        $client = Client::all()->first();

        $response = $this->post('/oauth/token',[
            "username" => "persona@email.com",
            "password" => "contra1234",
            "grant_type" => "password",
            "client_id" => "1",
            "client_secret" => $client -> secret
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            "token_type",
            "expires_in",
            "access_token",
            "refresh_token"
        ]);

        $response->assertJsonFragment([
            "token_type" => "Bearer"
        ]);

    }

    public function test_iniciar_sesion_con_client_invalido()
    {
         
        $response = $this->post('/oauth/token',[
            "grant_type" => "password",
            "client_id" => "999",
            "client_secret" => "qafasgf"
        ]);

        $response->assertStatus(401);
        $response->assertJsonFragment([
            "error" => "invalid_client",
            "error_description" => "Client authentication failed",
            "message" => "Client authentication failed"
        ]);
    }

    public function test_iniciar_sesion()
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

        $client = Client::all()->first();
        $response = $this -> post("/oauth/token",[
            "username" => "usuario@usuario",
            "password" => "1234",
            "grant_type" => "password",
            "client_id" => "1",
            "client_secret" => $client -> secret
        ]);
        $response->assertStatus(200);
    }

    public function test_cerrar_sesion_sin_autenticarse()
    {
        $response = $this->get('/api/v1/logout');
        $response->assertStatus(500);
    }

    public function test_cerrar_sesion_con_token_invalido()
    {
        $response = $this->get('/api/v1/logout',[
            [ "Authorization" => "Token Roto"]
        ]);
        $response->assertStatus(500);
    }

    public function test_cerrar_sesion()
    {
        $user = User::factory() -> create([
            "email" => "diego@gmail.com",
            "password" => Hash::make("contra1234")
        ]);
        Persona::factory() -> create([
            "id" => $user -> id,
            "nombre" => "Diego",
            "apellido" => "Rodriguez"
        ]);
        Administrador::factory() -> create([
            "id" => $user -> id
        ]);
        $client = Client::all()->first();

        $response = $this -> post("/oauth/token",[
            "username" => "usuario@usuario",
            "password" => "1234",
            "grant_type" => "password",
            "client_id" => "1",
            "client_secret" => $client -> secret
        ]);
        $response->assertStatus(200);
        $token = json_decode($response -> content(),true);
        $response = $this->get('/api/v1/logout',
            [ "Authorization" => "Bearer " . $token ['access_token']]
        );
        $response->assertStatus(200);
        $response->assertExactJson(
            ['message' => 'Sesión cerrada satisfactoriamente']
        );
    }

    public function test_obtener_rol(){
        $user = User::factory() -> create([
            "email" => "sofiaperez02@gmail.com",
            "password" => Hash::make("contra1234")
        ]);
        Persona::factory() -> create([
            "id" => $user -> id,
            "nombre" => "Sofia",
            "apellido" => "Perez"
        ]);
        Funcionario::factory() -> create([
            "id" => $user -> id
        ]);
        $response = $this->actingAs($user, "api")->get('/api/v1/rol');
        $response->assertStatus(200);
        $response->assertSeeText("funcionario");
    }

    public function test_obtener_rol_sin_autenticarse(){
        $response = $this->get('/api/v1/rol', [
            "Accept" => "application/json"
        ]);
        $response->assertStatus(401);
    }

    public function test_obtener_nombre(){
        $user = User::factory() -> create([
            "email" => "otrasofiaperez@gmail.com",
            "password" => Hash::make("contra1234")
        ]);
        Persona::factory() -> create([
            "id" => $user -> id,
            "nombre" => "Sofia",
            "apellido" => "Perez"
        ]);
        Conductor::factory() -> create([
            "id" => $user -> id
        ]);
        $response = $this->actingAs($user, "api")->get('/api/v1/nombre');
        $response->assertStatus(200);
        $response->assertSeeText("Sofia Perez");
    }
    
    public function test_obtener_nombre_sin_autenticarse(){
        $response = $this->get('/api/v1/nombre', [
            "Accept" => "application/json"
        ]);
        $response->assertStatus(401);
    }
}
