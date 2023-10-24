@extends('layouts.main')
@section('title', __('Form'))
@section('breadcrumb')
    <div class="col-md-12">
        <div class="page-header-title">
            <h4 class="m-b-10">{{ __('Crear Formulario') }}</h4>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item">{!! Html::link(route('home'), __('Dashboard'), []) !!}</li>
            <li class="breadcrumb-item">{!! Html::link(route('forms.index'), __('Fommulario'), []) !!}</li>
            <li class="breadcrumb-item active"> {{ __('Crear Formulario') }} </li>
        </ul>
    </div>
@endsection
@section('content')
    <div class="row">
        {!! Form::open([
            'route' => ['forms.store'],
            'method' => 'POST',
            'data-validate',
            'id' => 'payment-form',
            'class' => 'form-horizontal',
            'enctype' => 'multipart/form-data',
        ]) !!}
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h5>{{ __('General') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="col-lg-12">
                            <div class="form-group">
                                {{ Form::label('title', __('Título Del Formulario'), ['class' => 'form-label']) }}
                                {!! Form::text('title', null, [
                                    'class' => 'form-control',
                                    'id' => 'password',
                                    'placeholder' => __('Especifique el Título Del Formulario'),
                                ]) !!}
                                @if ($errors->has('form'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('form') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="">Dependencia</label>
                                        <select class="form-select" name="selectDependencia">
                                        <option value="">Selecciona una opción</option>
                                        <option value="DSPM">DSPM</option>
                                        <option value="DOIUM">DOIUM</option>
                                        <option value="TEST">TEST</option>
                                        </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                        <label for="">Departamento</label>
                                        <select class="form-select" name="selectDepartamento">
                                        <option value="">Selecciona una opción</option>
                                        <option value="area 1">Area 1</option>
                                        <option value="area 2">Area 2</option>
                                        </select>
                                    </div>
                            </div>
                    </div>


                        <div class="col-lg-12">
                            <div class="form-group">
                                {{ Form::label('form_logo', __('Seleccione Logotipo'), ['class' => 'form-label']) }}
                                {!! Form::file('form_logo', ['class' => 'form-control']) !!}
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                {{ Form::label('form_description', __('Breve Descripción'), ['class' => 'form-label']) }}
                                {!! Form::textarea('form_description', null, [
                                    'id' => 'form_description',
                                    'placeholder' => __('Ingresa Breve Descripción'),
                                    'rows' => '3',
                                    'class' => 'form-control',
                                ]) !!}
                                @if ($errors->has('form_description'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('form_description') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                {{ Form::label('success_msg', __('Mensaje De Éxito'), ['class' => 'form-label']) }}
                                {!! Form::textarea('success_msg', null, [
                                    'id' => 'success_msg',
                                    'placeholder' => __('Ingresa Mensaje De Éxito'),
                                    'class' => 'form-control',
                                ]) !!}
                                @if ($errors->has('success_msg'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('success_msg') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                      
                        
                        <div class="col-lg-12">
                            <div class="form-group">
                                {{ Form::label('allow_comments', __('Permitir comentarios'), ['class' => 'form-label']) }}
                                <label class="mt-2 form-switch float-end custom-switch-v1">
                                    <input type="checkbox" name="allow_comments" id="allow_comments"
                                        class="form-check-input input-primary" {{ 'unchecked' }}>
                                </label>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                {{ Form::label('allow_share_section', __('Permitir Compartir Sección'), ['class' => 'form-label']) }}
                                <label class="mt-2 form-switch float-end custom-switch-v1">
                                    <input type="checkbox" name="allow_share_section" id="allow_share_section"
                                        class="form-check-input input-primary" {{ 'unchecked' }}>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="text-end">
                            {!! Html::link(route('forms.index'), __('Cancelar'), ['class' => 'btn btn-secondary']) !!}
                            {!! Form::button(__('Guardar'), ['type' => 'submit', 'class' => 'btn btn-primary']) !!}
                        </div>
                    </div>
                </div>
            
            </div>
           
        </div>
        {!! Form::close() !!}
    </div>
@endsection
@push('style')
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/daterangepicker/daterangepicker.css') }}" />
    <link href="{{ asset('vendor/bootstrap-tagsinput/dist/bootstrap-tagsinput.css') }}" rel="stylesheet" />
@endpush
@push('script')
    <script src="{{ asset('vendor/moment.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/choices.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('vendor/daterangepicker/daterangepicker.min.js') }}"></script>
    <script src="https://www.google.com/recaptcha/api.js"></script>
    <script src="{{ asset('vendor/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js') }}"></script>
  
    <script src="{{ asset('vendor/ckeditor/ckeditor.js') }}"></script>

    <script>
        CKEDITOR.replace('success_msg', {
            filebrowserUploadUrl: "{{ route('ckeditors.upload', ['_token' => csrf_token()]) }}",
            filebrowserUploadMethod: 'form'
        });
        CKEDITOR.replace('thanks_msg', {
            filebrowserUploadUrl: "{{ route('ckeditors.upload', ['_token' => csrf_token()]) }}",
            filebrowserUploadMethod: 'form'
        });
    </script>

   
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var genericExamples = document.querySelectorAll('[data-trigger]');
            for (i = 0; i < genericExamples.length; ++i) {
                var element = genericExamples[i];
                new Choices(element, {
                    placeholderValue: 'This is a placeholder set in the config',
                    searchPlaceholderValue: 'This is a search placeholder',
                });
            }
        });
    </script>
@endpush
