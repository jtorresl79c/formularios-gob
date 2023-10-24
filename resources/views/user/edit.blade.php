@extends('layouts.main')
@section('content')

<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Usuarios / </span>Editar</h4>

@if (session('error'))
  <div class="alert alert-danger" role="alert">
  {{ session('error') }}
  </div>
@endif


<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
        <h5 class="card-header">DATOS DEL RESPONSABLE</h5>
        <div class="card-body">
        <form action="{{ route('usuarios.update',$usuarios->id) }}" method="POST">
                {{ csrf_field() }}
                @method('PUT')
            <div class="row"> 
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Nombre</label>
                        <input type="text" class="form-control" name="nombre" placeholder="Ingrese el nombre comercial" 
                        value="{{old("name", $usuarios->name)}}" required>
                        @foreach ($errors->get('nombre') as $res)
                        <div  class="form-text text-danger">{{ $res }}</div>
                        @endforeach
                    </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                        <label class="form-label">Correo</label>
                        <input type="text" class="form-control" name="email" placeholder="Ingrese razón social" 
                        value="{{old("email", $usuarios->email)}}" required>
                        @foreach ($errors->get('email') as $res)
                        <div  class="form-text text-danger">{{ $res }}</div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="row mb-3"> 
                <div class="col-md-6">
                        <button class="btn btn-warning me-1 collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                         Cambiar contraseña
                        </button>

                        @foreach ($errors->get('password_confirm') as $res)
                                <div  class="form-text text-danger">{{ $res }}</div>
                        @endforeach  
                 </div>
              </div>

            <div class="row"> 
                <div class="collapse" id="collapseExample" style="">
                    <div class="d-grid d-sm-flex p-3 border">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Contraseña</label>
                                <input type="text" class="form-control" name="password" placeholder="Ingrese número telefónico">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Confirmar Contraseña</label>
                                <input type="text" class="form-control" name="password_confirm" placeholder="Ingrese número telefónico">                 
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-3"> 
                 <label class="col-md-12 col-form-label" for="rol_id">Responsable</label>
                    <div class="col-sm-12">
                        @php
                            $rol_id_final = empty(old('rol_id')) ? $usuarios->roles()->first()->id : old('rol_id');
                        @endphp
                        <select class="form-control" name="rol_id" id="rol_id" required>
                            @foreach ($roles as $rol)
                                <option value="{{ $rol->id }}" {{ $rol->id == $rol_id_final ? 'selected' : '' }}>{{ $rol->name }}</option>
                            @endforeach
                        </select>
                        @foreach ($errors->get('rol_id') as $message)
                            <div id="emailHelp" class="form-text text-danger">{{ $message }}</div>
                        @endforeach
                    </div>
            </div>

            <div class="row">
                <div class="col-sm-10">
                <input type="submit" class="btn btn-primary" value="Guardar">
                </div>
            </div>

            </div>
            </div>
        </form>
    </div>
</div>
@endsection

