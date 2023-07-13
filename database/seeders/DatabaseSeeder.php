<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Persona;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory(10)->create();
       
        for($i = 1; $i <= 10; $i++){
        Persona::factory()->create([
            "id" => $i,
            "nombre" => "elpepe",
            "apellido" => "etesech"
        ]);
        }
        
    }

}
