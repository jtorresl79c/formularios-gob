@php
    // $color = \App\Facades\UtilityFacades::getsettings('color');
    $user = \Auth::user();
    $color = $user->theme_color;
  
@endphp
@extends('layouts.main')
@section('title', __('Submitted Form'))
@section('breadcrumb')
    <div class="col-md-12">
        <div class="page-header-title">
            <h4 class="m-b-10">{{ __('Formularios enviados de' . ' ' . $forms_details->title) }}</h4>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item">{!! Html::link(route('home'), __('Dashboard'), []) !!}</li>
            <li class="breadcrumb-item active"> {{ __('Formularios enviados de' . ' ' . $forms_details->title) }} </li>
        </ul>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="main-content">
            <section class="section">
                @if (!empty($forms_details->logo))
                    <div class="text-center gallery gallery-md">
                        {!! Form::image(
                            Storage::exists($forms_details->logo)
                                ? asset('storage/app/' . $forms_details->logo)
                                : Storage::url('appLogo/78x78.png'),
                            null,
                            [
                                'class' => 'gallery-item float-none',
                                'id' => 'app-dark-logo',
                            ],
                        ) !!}
                    </div>
                @endif
                <h2 class="text-center">{{ $forms_details->title }}</h2>
                <div class="section-body filter">
                    <div class="row">
                        <div class="col-md-12 mt-4">
                            <div class="card">
                                <div class="card-body">
                                        <div class="row">
                                            <div class="col-lg-4 col-md-6 responsive-search">
                                                <div class="form-group d-flex justify-content-start">
                                                    {{ Form::text('user', null, ['class' => 'form-control mr-1 ', 'placeholder' => __('Search here'), 'data-kt-ecommerce-category-filter' => 'search']) }}
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-6 responsive-search">
                                                <div class="form-group row d-flex justify-content-start">
                                                    {{ Form::text('duration', null, ['class' => 'form-control mr-1 created_at', 'placeholder' => __('Select Date Range'), 'id' => 'pc-daterangepicker-1']) }}
                                                    {!! Form::hidden('form_id', $forms_details->id, ['id' => 'form_id']) !!}
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-6 btn-responsive-search">
                                                {{ Form::button(__('Filter'), ['class' => 'btn btn-primary btn-lg  add_filter button-left']) }}
                                                {{ Form::button(__('Clear Filter'), ['class' => 'btn btn-secondary btn-lg clear_filter']) }}
                                            </div>
                                        </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            
                                           <!--  <div class="table-responsive py-4"> -->
                                                
                                                {{ $dataTable->table(['width' => '100%']) }}

                                              
                                            <!-- </div> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-md-12" id="chart_div">
                                    <style>
                                        .pie-chart {
                                            width: 100%;
                                            height: 400px;
                                            margin: 0 auto;
                                            float: right;
                                        }

                                        .text-center {
                                            text-align: center;
                                        }

                                        @media (max-width: 991px) {
                                            .pie-chart {
                                                width: 100%;
                                            }
                                        }
                                    </style>
                                 

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection

@push('style')
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/daterangepicker/daterangepicker.css') }}" />
    @include('layouts.includes.datatable_css')
@endpush
<script id="details-template" type="text/x-handlebars-template">
    @verbatim
        <table class="table">
            <tr>
                <td>Full name:</td>
                <td>{{user}}</td>
            </tr>
            <tr>
                <td>Email:</td>
                <td>{{user}}</td>
            </tr>
            <tr>
                <td>Extra info:</td>
                <td>And any further details here (images etc)...</td>
            </tr>
            <tr>
                <td>JSON:</td>
                <td>
                    {{#each json}}
                        <div>Type: {{this.type}}</div>
                        <div>Label: {{this.label}}</div>
                        <div>Name: {{this.name}}</div>
                        <!-- Accede a otras propiedades aquí -->
                        <ul>
                            {{#each this.values}}
                                <li>Label: {{this.label}}, Value: {{this.value}}</li>
                            {{/each}}
                        </ul>
                    {{/each}}
                </td>
            </tr>
        </table>
    @endverbatim
</script>

@push('script')
    <script src="{{ asset('assets/js/loader.js') }}"></script>
    <script src="{{ asset('vendor/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/flatpickr.min.js') }}"></script>
    <script src="{{ asset('vendor/apex-chart/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/choices.min.js') }}"></script>


    <script type="text/javascript" src="{{ asset('vendor/daterangepicker/daterangepicker.min.js') }}"></script>
    @include('layouts.includes.datatable_js')
    {{ $dataTable->scripts() }}
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize your DataTable
        $('#forms-table tbody').on('click', 'expand-row', function () {
            var tr = $(this).closest('tr');
            var row = dataTable.row(tr);

            if (row.child.isShown()) {
                // This row is already expanded - close it
                row.child.hide();
                tr.removeClass('shown');
                console.log('cargando');
            } else {
                // Expand the row to show additional content
                row.child(formatRowDetails(row.data())).show();
                tr.addClass('shown');
            }
        });

        function formatRowDetails(data) {
            // Define the additional content to be displayed
            var details = 'Additional details go here'; // Replace with your actual content
            return '<div class="row-details">' + details + '</div>';
        }
    });
</script>
@endpush

