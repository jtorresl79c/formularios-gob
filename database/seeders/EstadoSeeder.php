<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EstadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $estado = new \App\Models\Estado();
        $estado->nombre = 'Activo';
        $estado->save();
        
        $estado = new \App\Models\Estado();
        $estado->nombre = 'Inactivo';
        $estado->save();
    }
}
