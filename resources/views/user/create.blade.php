@extends('layouts.main')
@section('content')

<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Usuarios / </span>Agregar</h4>

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
            <form method="POST" class="mb-3" action="{{ route('usuarios.store') }}">
            @csrf
            <div class="row"> 
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Nombre</label>
                        <input type="text" class="form-control" name="nombre" placeholder="Ingrese el nombre comercial" 
                        value="{{old("nombre")}}" required>
                        @foreach ($errors->get('nombre') as $res)
                        <div  class="form-text text-danger">{{ $res }}</div>
                        @endforeach
                    </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                        <label class="form-label">Correo</label>
                        <input type="text" class="form-control" name="email" placeholder="Ingrese razón social" 
                        value="{{old("email")}}" required>
                        @foreach ($errors->get('email') as $res)
                        <div  class="form-text text-danger">{{ $res }}</div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="row"> 
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Contraseña</label>
                        <input type="text" class="form-control" name="password" placeholder="Ingrese número telefónico" 
                        value="{{old("password")}}" required>  
                        @foreach ($errors->get('password') as $res)
                        <div  class="form-text text-danger">{{ $res }}</div>
                        @endforeach                   
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Confirmar Contraseña</label>
                        <input type="text" class="form-control" name="password_confirm" placeholder="Ingrese número telefónico" 
                        value="{{old("password_confirm")}}" required>  
                        @foreach ($errors->get('password_confirm') as $res)
                        <div  class="form-text text-danger">{{ $res }}</div>
                        @endforeach                   
                    </div>
                </div>
            </div>

            <div class="row mb-3"> 
                 <label class="col-md-12 col-form-label" for="rol_id">Responsable</label>
                            <div class="col-sm-12">
                                <select class="form-control" name="rol_id" id="rol_id" required>
                                    <option selected value="">Seleccione responsable</option>
                                    @foreach ($roles as $rol)
                                        <option value="{{$rol->id}}">{{$rol->name}}</option>
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

