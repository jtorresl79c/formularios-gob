<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $this->call(EstadoSeeder::class);
        $this->call(RolSeeder::class);
        $this->call(UserSeeder::class);
        
       /*  $this->call(ChoferSeeder::class);
        $this->call(ProductoSeeder::class);
        $this->call(ResponsableSeeder::class);
        $this->call(ProveedorSeeder::class);
        $this->call(RemisionSeeder::class);
        $this->call(TarimaTiposSeeder::class);
        $this->call(CajasSeeder::class); */
    }
}
