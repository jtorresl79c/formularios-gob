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
            <form method="POST" class="mb-3" action="{{ route('roles.store') }}">
            @csrf
            

            <div class="row"> 
    <div class="col-md-6">
        <div class="mb-3">
        <label class="form-label">Nombre</label>
        <input type="text" class="form-control" name="name" placeholder="Nombre de Rol" 
        value="{{old("name")}}" required>
        @foreach ($errors->get('name') as $res)
        <div  class="form-text text-danger">{{ $res }}</div>
        @endforeach
        </div>
    </div>
</div>

<div class="row"> 
    <div class="col-md-12">
        <h2>Lista de permisos</h2>
        @foreach ($permissions as $per)
        <div>
            <label>
            <input type="checkbox" name="permissions[]" value="{{ $per->id }}">
                {{$per->description}}
            </label>
        </div>
        @endforeach
    </div>
</div>



            <div class="row">
                <div class="col-sm-10">
                    <input type="submit" class="btn btn-primary" value="Crear Rol">
                </div>
            </div>
            </div>
            </div>
        </form>
    </div>
</div>
@endsection

