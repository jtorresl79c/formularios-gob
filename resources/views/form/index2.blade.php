@extends('layouts.main')
@section('title', __('Formulario'))
@section('breadcrumb')
    <div class="col-md-12">
        <div class="page-header-title">
            <h4 class="m-b-10">{{ __('Formulario') }}</h4>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item">{!! Html::link(route('home'), __('Dashboard'), []) !!}</li>
            <li class="breadcrumb-item active"> {{ __('Formulario') }} </li>
        </ul>
        <div class="float-end">
            <div class="d-flex align-items-center">
              <a href="forms/create" class="btn btn-primary me-2">NUEVO FORMULARIO</a>
            </div>
        </div>
    </div>
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body table-border-style">
                    
                <div id="loading2" class="text-center" style="display:none;" >
                        <span class="loader"></span>
                        <h4 class="p-4">CARGANDO...</h4>
                      </div>

                        <form id="formV">
                          <div class="row p-4">

                          <div class="col-md-4">
                            <label for="">Dependencia</label>
                            <select class="form-select" name="selectDependencia" required>
                              <option value="">Selecciona una opción</option>
                              <option value="DSPM">DSPM</option>
                              <option value="DOIUM">DOIUM</option>
                            </select>
                          </div>
                          <div class="col-md-4">
                            <label for="">Departamento</label>
                            <select class="form-select" name="selectDepartamento" required>
                              <option value="">Selecciona una opción</option>
                              <option value="area 1">Area 1</option>
                              <option value="area 2">Area 2</option>
                            </select>
                          </div>
                          <div class="col-md-4">
                            <br>
                              <button class="btn btn-primary" style="align-items: center; display: flex;" type="submit">Buscar</button>
                          </div>
                        </div>

                        </form>

                      <table class="table table-striped table-borderless border-bottom" width="100%">
                        <thead>
                          <th>No.</th>
                          <th>Titulo</th>
                          <th>Estado</th>
                          <th>Creado</th>
                          <th>Acciones</th>
                        </thead>
                        <tbody id="tbodyForm">
                        </tbody>
                      </table>
                    </div>



                </div>
            </div>
        </div>
    </div>
@endsection
@push('style')
    @include('layouts.includes.datatable_css')
@endpush
@push('script')
<script>
  $(document).ready(function(){
      $("#formV").submit(function(e){
          e.preventDefault();
          var csrfToken = $('meta[name="csrf-token"]').attr('content');
          var dep = $('select[name="selectDependencia"]').val();
          var depto = $('select[name="selectDepartamento"]').val();
          forms(dep, depto, csrfToken);
      });
  
      function forms(dep, depto,csrfToken){



$.ajax({
  url: "/obtener-datos",
  type: "POST",
  data: {dep:dep, depto:depto,  _token: csrfToken},
  beforeSend: function(){
    $("#loading2").show();
  },
  success: function(e){
    // console.log(e);


    var datos = e.data;
    let template = '';
    let color = '';
    let checked = '';
    var no = 0;


    datos.forEach(dato => {
      no++;
      $("#loading2").hide();

      if (dato.idestatus == 1) {
        checked = 'checked';
      }else{
        checked = '';
      }

      template += `<tr id="${dato.idform}">
      <td>${no}</td>
      <td>${dato.titulo}</td>
      <td>${dato.estado}</td>
      <td>${dato.fechaCreacion}</td>
      <td>
        <form action="fillform.php" method="POST">
          <input type="hidden" name="bd" value="${dato.dep}">
          <input type="hidden" name="schema" value="${dato.depto}">
          <input type="hidden" name="tabla" value="${dato.titulo}">
          <button type="submit" class="btn btn-warning btn-sm" title="Llenar"> <i class='bx bx-edit'></i> </button>
        </form>
      </td>
      </tr>`;
    });


    // <form action="designForm.php" method="POST">
    //   <input type="hidden" name="diseñar" value="${dato.idform}">
    //   <button type="submit" class="btn btn-warning btn-sm" title="Llenar"> <i class='bx bx-edit'></i> </button>
    // </form>

    $("#tbodyForm").html(template);

    //   <a href="designForm" class="btn btn-warning btn-sm"><i class='bx bx-edit'></i></a>

  }
});

}
  });
  </script>
    @include('layouts.includes.datatable_js')
    <script>
        function copyToClipboard(element) {
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val($(element).attr('data-url')).select();
            document.execCommand("copy");
            $temp.remove();
            show_toastr('Great!', '{{ __('Copy Link Successfully.') }}', 'success',
                '{{ asset('assets/images/notification/ok-48.png') }}', 4000);
        }
        $(function() {
            $('body').on('click', '#share-qr-code', function() {
                var action = $(this).data('share');
                var modal = $('#common_modal2');
                $.get(action, function(response) {
                    modal.find('.modal-title').html('{{ __('QR Code') }}');
                    modal.find('.modal-body').html(response.html);
                    feather.replace();
                    modal.modal('show');
                })
            });
        });
    </script>
@endpush
