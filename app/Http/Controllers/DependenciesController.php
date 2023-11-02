<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dependency;
use Illuminate\Support\Facades\DB;

class DependenciesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dependencies = Dependency::all();
        return view('dependencies.index',compact(['dependencies']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dependencies.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $dependencyName = strtolower($request->name);

        // Set the configuration to connect to the "cluster" database
        $databaseConnection = config('database.connections.pgsql2');
        $databaseConnection['database'] = 'cluster';
        config(['database.connections.pgsql2' => $databaseConnection]);
        // Create a connection to the "cluster" database
        $bd = DB::connection('pgsql2');


        $canCreateDatabaseQuery = "SELECT 'crear' AS EJECUTAR WHERE NOT EXISTS (SELECT 1 FROM pg_database WHERE datname = :dep)";
        $createDatabase = $bd->select($canCreateDatabaseQuery, ['dep' => $dependencyName]);
        
        if (count($createDatabase) > 0) {
            $createDatabaseQuery = "CREATE DATABASE $dependencyName WITH OWNER = sig ENCODING = 'UTF8' CONNECTION LIMIT = -1 IS_TEMPLATE = False";
            $bd->statement($createDatabaseQuery);
        }

        DB::purge('pgsql2');
        

        $dependency = new Dependency;
        $dependency->name = $dependencyName;
        $dependency->save();
        return redirect()->route('dependencies.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
