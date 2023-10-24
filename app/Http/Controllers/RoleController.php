<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{

    public function index()
    {
        $roles = Role::all();
       return view('role.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permissions = Permission::all();
        return view('role.create',compact('permissions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
        ]);
        $role = Role::create($request->all());

        $role->permissions()->sync($request->permissions);

        return redirect()->route('roles.edit', $role)->with('status', 'Se ha registrado correctamente.');
    }

   
    public function show(Role $role)
    {
       return view('role.show', compact('role'));
    }

    public function edit(Role $role)
    {
        $permissions = Permission::all();
        return view('role.edit', compact('role','permissions'));
    }

  
    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => 'required',
        ]);
        $role->update($request->all());

        $role->permissions()->sync($request->permissions);

        return redirect()->route('roles.edit', $role)->with('status', 'El rol se ha actualizado con exito.');
    }

   
    public function destroy(Role $role)
    {
        $role->delete();

        return redirect('/roles')->with('status', 'Se ha eliminado correctamente.');
       /*  return back()->with('status', 'Se ha eliminado correctamente.'); */
    }
}
