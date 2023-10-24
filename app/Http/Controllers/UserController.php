<?php

namespace App\Http\Controllers;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Hash;
use Auth;

use Faker\Factory as Factory;

class UserController extends Controller
{
    var $ACTIVO, $INACTIVO;

    public function __construct() {
        $this->ACTIVO = env("ACTIVO", "1");
        $this->INACTIVO = env("INACTIVO", "2");
    }
    
    public function index()
    {
        $usuario = User::with('roles')->Where("estado_id", $this->ACTIVO)->orderBy('id', 'desc')->get();

        return view('user.index',compact('usuario'));
    }


    public function create()
    {
        $roles = Role::all();
        return view('user.create', compact(['roles']));
    }

 
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required',
            'email' => 'required',
            'password' => 'required',
            'rol_id' => 'required',
        ]);
        if($request->password == $request->password_confirm){

            $resp = new User;
            $resp->name = $request->nombre;
            $resp->email = $request->email; 
            $resp->password = bcrypt($request->password);
            $resp->estado_id = $this->ACTIVO;
            $resp->save();
            $resp->assignRole($request->rol_id);
            return redirect('/usuarios')->with('status', 'Se ha registrado correctamente.');
        }else{
            return redirect()->route('usuarios.create')->with('error', 'Las contraseñas no coinciden');
        }
    }

 
    public function show(User $User)
    {
        //
    }

    public function edit($id)
    { 
        $usuarios = User::find($id);
        $roles = Role::all();
        return view('user.edit', compact('usuarios','roles'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nombre' => 'required',
            'email' => 'required',
            'rol_id' => 'required',
            'password' => '',
            'password_confirm' => 'nullable|same:password'
        ]);

        $resp = User::find($id); 
        $resp->name = $request->nombre;
        $resp->email = $request->email; 
        if($request->password){
            $resp->password = bcrypt($request->password);
        }
        $resp->roles()->sync($request->rol_id);
        $resp->estado_id = $this->ACTIVO;
        $resp->save();
       return redirect('/usuarios')->with('status', 'Se ha actualizado correctamente.');
    }

    public function destroy($id)
    {
       $resp = User::find($id);
       $resp->estado_id = $this->INACTIVO;
       $resp->save();
       return back()->with('status', 'Se ha eliminado correctamente.');
    }
}
