<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       $role1= Role::create(['name' => 'Admin' ]);
       $role2=Role::create(['name' => 'Capturista' ]);
       $role3=Role::create(['name' => 'Etiquetador' ]);

       $permission = Permission::create(['name' => 'remision.index','description' => 'Modulo Remision'])->syncRoles([$role1,$role2,$role3]);
       $permission = Permission::create(['name' => 'proveedor.index','description' => 'Modulo Proveedor'])->syncRoles([$role1,$role2]);
       $permission = Permission::create(['name' => 'chofer.index','description' => 'Modulo Chofer'])->syncRoles([$role1,$role2]);
       $permission = Permission::create(['name' => 'tarima.index','description' => 'Modulo Tarimas'])->syncRoles([$role1]);
       $permission = Permission::create(['name' => 'producto.index','description' => 'Modulo Productos'])->syncRoles([$role1,$role2]);
       $permission = Permission::create(['name' => 'responsable.index','description' => 'Modulo Responsables'])->syncRoles([$role1]);
       $permission = Permission::create(['name' => 'usuarios.index','description' => 'Modulo de Usuarios'])->syncRoles([$role1]);
        /* $rol = new \App\Models\Rol();
        $rol->role_name = 'Administrador';
        $rol->save();
        
        $rol = new \App\Models\Rol();
        $rol->role_name = 'Capturista de datos';
        $rol->save(); */

      /*   $rol = new \App\Models\Rol();
        $rol->role_name = 'Etiquetador';
        $rol->save();

        $rol = new \App\Models\Rol();
        $rol->role_name = 'Supervisor';
        $rol->save();

        $rol = new \App\Models\Rol();
        $rol->role_name = 'Empleado';
        $rol->save(); */
    }
}
