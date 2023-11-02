<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Dependency;
use App\Models\Department;
use Illuminate\Support\Facades\DB;

class DepartmentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $departments = Department::with('dependency')->get();
        return view('departments.index', compact(['departments']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $dependencies = Dependency::all();
        return view('departments.create', compact(['dependencies']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $departmentName = $request->name;
        $dependencyId = $request->dependency_id;

        $dependency = Dependency::find($dependencyId);
        $dependencyName = $dependency->name;

        $databaseConnection = config('database.connections.pgsql2');
        $databaseConnection['database'] = $dependencyName;
        config(['database.connections.pgsql2' => $databaseConnection]);
        $bd = DB::connection('pgsql2');

        $createSchemaQuery = "CREATE SCHEMA IF NOT EXISTS $departmentName AUTHORIZATION sig";
        $bd->statement($createSchemaQuery);

        $department = new Department;
        $department->name = $departmentName;
        $department->dependency_id = $dependencyId;
        $department->save();

        return redirect()->route('departments.index');
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
