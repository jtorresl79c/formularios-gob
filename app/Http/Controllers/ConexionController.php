<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConexionController extends Controller
{
    public function obtenerDatos(Request $request)
    {
      
        $dep = strtolower(str_replace(' ', '_', $request->dep));
        $depto = strtolower(str_replace(' ', '_', $request->depto));
        
        $conexionConfig = config('database.connections.pgsql2');
        $conexionConfig['database'] = $dep; // Asigna el nombre de la base de datos
        config(['database.connections.pgsql2' => $conexionConfig]);
        
        $bd = DB::connection('pgsql2');
        
        $query1 = "SELECT * FROM pg_catalog.pg_tables where schemaname = '".$depto."'";
        $query = $bd->select($query1);
        
        $json = [];
        
        if (!empty($query)) {
            foreach ($query as $dato) {
                $json[] = [
                    'dep' => $dep,
                    'depto' => $depto,
                    'titulo' => $dato->tablename
                ];
            }
        }
        
        return response()->json(['data' => $json]);
        
    }
}
