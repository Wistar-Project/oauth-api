<?php

namespace Tests\Feature;

use Laravel\Passport\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;

class PersonaTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_crearFuncionario()
    {
        $response = $this->post('api/v1/user', [
            'email' => 'prueba@test',
            'rol'=> 'funcionario',
            'nombre'=>'pruebita',
            'apellido'=>'gomez',
            'password' => '1234',
            'password_confirmation' => '1234'
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('users', [
            'email' => 'prueba@test',
        ]);
        $this->assertDatabaseHas('personas', [
            'nombre' => 'pruebita',
        ]);
    }

    public function test_crearAdministrador()
    {
        $response = $this->post('api/v1/user', [
            'email' => 'prueba@admin',
            'rol'=> 'administrador',
            'nombre'=>'pruebita',
            'apellido'=>'gomez',
            'password' => '1234',
            'password_confirmation' => '1234'
        ]);
        $this->assertDatabaseHas('users', [
            'email' => 'prueba@admin',
        ]);
        $this->assertDatabaseHas('personas', [
            'nombre' => 'pruebita',
        ]);
        $response->assertStatus(200);
    }
    public function test_crearConductor()
    {
        $response = $this->post('api/v1/user', [
            'email' => 'prueba@conductor',
            'rol'=> 'conductor',
            'nombre'=>'pruebita',
            'apellido'=>'gomez',
            'password' => '1234',
            'password_confirmation' => '1234'
        ]);
        $this->assertDatabaseHas('users', [
            'email' => 'prueba@conductor',
        ]);
        $this->assertDatabaseHas('personas', [
            'nombre' => 'pruebita',
        ]);
        $response->assertStatus(200);
    }
    public function test_iniciarSesion()
    {
        $response = $this->post('api/v1/user', [
            'email' => 'prueba@conductor',
            'rol'=> 'conductor',
            'nombre'=>'pruebita',
            'apellido'=>'gomez',
            'password' => '1234',
            'password_confirmation' => '1234'
        ]);
        $this->assertDatabaseHas('users', [
            'email' => 'prueba@conductor',
        ]);
        $this->assertDatabaseHas('personas', [
            'nombre' => 'pruebita',
        ]);
        
        
        Artisan::call('passport:client',[
            '--password' => true,
            '--no-interaction'=>true,
            '--name'=>'Test Client',
        ]);        
        $client = Client::findOrFail(1);
        $response = $this->post('oauth/token', [
            'username' => 'prueba@conductor',
            'password' => '1234',
            'grant_type' => 'password',
            'client_id' => '1',
            "client_secret" => $client -> secret
        ]);
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
}
