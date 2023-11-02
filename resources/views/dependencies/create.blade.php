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
            <li class="breadcrumb-item active"> {{ __('Crear') }} </li>
        </ul>
        <div class="float-end">
            {{-- <div class="d-flex align-items-center">
                <a class="btn btn-primary" href="{{ route('dependencies.create') }}" role="button">Crear</a>
            </div> --}}
        </div>
    </div>
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-12">
            <form action="{{route('dependencies.store')}}" method="POST">
                @csrf
                <input type="text" name="name">
                <button type="submit">Enviar</button>
            </form>
        </div>
    </div>
@endsection
@push('style')
    
@endpush
@push('script')
    
@endpush
