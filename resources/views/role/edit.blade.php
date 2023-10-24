@extends('layouts.main')
@section('content')

@if (session('status'))
  <div class="alert alert-success" role="alert">
  {{ session('status') }}
  </div>
@endif


<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Rol / </span>Actualizar</h4>

@if (session('error'))
  <div class="alert alert-danger" role="alert">
  {{ session('error') }}
  </div>
@endif
<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
        <h5 class="card-header">DATOS DEL ROL</h5>
        <div class="card-body">
            <form action="{{ route('roles.update',$role->id) }}" method="POST">
            {{ csrf_field() }}
            @method('PUT')
            

            <div class="row"> 
    <div class="col-md-6">
        <div class="mb-3">
        <label class="form-label">Nombre</label>
        <input type="text" class="form-control" name="name" placeholder="Nombre de Rol" 
        value="{{old("name",$role->name)}}" required>
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
            <input type="checkbox" name="permissions[]" value="{{ $per->id }}" @if($role->permissions->contains($per)) checked @endif>
                {{$per->description}}
            </label>
        </div>
        @endforeach
    </div>
</div>



            <div class="row">
                <div class="col-sm-10">
                    <input type="submit" class="btn btn-primary" value="Editar Rol">
                </div>
            </div>
            </div>
            </div>
            </form>
    </div>
</div>
@endsection

