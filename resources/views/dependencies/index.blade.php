@extends('layouts.main')
@section('title', __('Formulario'))
@section('breadcrumb')
    <div class="col-md-12">
        <div class="page-header-title">
            <h4 class="m-b-10">{{ __('Formulario') }}</h4>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item">{!! Html::link(route('home'), __('Dashboard'), []) !!}</li>
            <li class="breadcrumb-item active"> {{ __('Dependencias') }} </li>
        </ul>
        <div class="float-end">
            <div class="d-flex align-items-center">
                <a class="btn btn-primary" href="{{ route('dependencies.create') }}" role="button">Crear</a>
            </div>
        </div>
    </div>
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-12">
            <table class="table">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nombre</th>
                  </tr>
                </thead>
                <tbody>
                    @foreach ($dependencies as $dependency)
                        <tr>
                            <th scope="row">{{$dependency->id}}</th>
                            <td>{{$dependency->name}}</td>
                        </tr>
                    @endforeach
                </tbody>
              </table>
        </div>
    </div>
@endsection
@push('style')
    
@endpush
@push('script')
    
@endpush
