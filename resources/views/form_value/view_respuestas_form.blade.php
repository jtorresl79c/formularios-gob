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
                                        <div class="col-md-12">

                                               <table class="table table-bordered" id="customers-table">
                                                   <thead>
                                                       <tr>
                                                           <th></th>
                                                           <th>Id</th>
                                                           <th>First name</th>
                                                           <th>Last name</th>
                                                           <th>Email</th>
                                                           <th>Created At</th>
                                                           <th>Updated At</th>
                                                       </tr>
                                                   </thead>
                                               </table>
                                            <!-- </div> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

@endsection


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <script id="details-template" type="text/x-handlebars-template">
        @verbatim
            <table class="table">
                <tr>
                    <td>Full name:</td>
                    <td>{{first_name}}</td>
                </tr>
                <tr>
                    <td>Email:</td>
                    <td>{{email}}</td>
                </tr>
                <tr>
                    <td>Extra info:</td>
                    <td>And any further details here (images etc)...</td>
                </tr>
            </table>
        @endverbatim
    </script>

    <script>
        $(document).ready(function() {
    // Tu código que utiliza Handlebars
           var template = Handlebars.compile($("#details-template").html());
         
          var table = $('#customers-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('api.row_details',$form_id) }}',
            columns: [
              {
                "className":      'details-control',
                "orderable":      false,
                "searchable":     false,
                "data":           null,
                "defaultContent": ''
              },
              { data: 'id', name: 'id' },
              { data: 'first_name', name: 'first_name' },
              { data: 'last_name', name: 'last_name' },
              { data: 'email', name: 'email' },
              { data: 'created_at', name: 'created_at' },
              { data: 'updated_at', name: 'updated_at' },
            ],
            order: [[1, 'asc']]
          });

          $('#customers-table tbody').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = table.row( tr );

            if ( row.child.isShown() ) {
              // This row is already open - close it
              row.child.hide();
              tr.removeClass('shown');
            }
            else {
              // Open this row
              row.child( template(row.data()) ).show();
              tr.addClass('shown');
            }
          });
        });
    </script>






@push('style')
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/daterangepicker/daterangepicker.css') }}" />
    @include('layouts.includes.datatable_css')
@endpush


@push('script')
    <script src="{{ asset('assets/js/loader.js') }}"></script>
    <script src="{{ asset('vendor/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/flatpickr.min.js') }}"></script>
    <script src="{{ asset('vendor/apex-chart/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/choices.min.js') }}"></script>

    

    <script type="text/javascript" src="{{ asset('vendor/daterangepicker/daterangepicker.min.js') }}"></script>


    @include('layouts.includes.datatable_js')
@endpush

@push('scripts')
<script>
    if ($.fn.DataTable.isDataTable('#forms-table')) {
    var table = $('#forms-table').DataTable();
    // Resto del código para expandir filas
} else {
    console.error('DataTables is not initialized on #forms-table');
}

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

