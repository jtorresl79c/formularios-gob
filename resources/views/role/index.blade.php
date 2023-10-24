@extends('layouts.main')
  @section('content')
  <div class="row mb-4">
    <div class="col">
        <div class="d-flex align-items-center justify-content-between">
            <h4 class="fw-bold py-3 m-0">Usuarios</h4>
            @can('usuarios.index')
            <a href="{{ route('roles.create') }}" class="btn btn-primary"></i>Agregar</a>
            @endcan

        </div>
    </div>
</div>

@if (session('status'))
  <div class="alert alert-success" role="alert">
  {{ session('status') }}
  </div>
@endif

  <div class="row">
  <div class="card">  
      <div class="table-responsive text-nowrap">
      <table class="table">
        <thead>
          <tr>
            <th>ID</th>
            <th>ROL</th>
            <th colspan="2" width="32%">Acciones</th>
          </tr>
        </thead>
        <tbody class="table-border-bottom-0">
          @foreach ($roles as $rol)
            <tr>
              <td>{{$rol->id}}</td>
              <td>{{$rol->name}}</td>
              <td>
                <div class="d-flex align-items-center">
                    <a class="btn btn-info me-1" href="{{ route('roles.edit', $rol->id) }}"
                        title="Editar">
                        <i class="bi bi-pencil-square"></i>
                        Editar
                    </a>
                    <form method="POST" class="m-0" action="{{ route('roles.destroy', $rol->id) }}">
                        @csrf
                        <input name="_method" type="hidden" value="DELETE">
                        <button type="submit" class="btn btn-danger show-alert"
                            data-toggle="tooltip" title='Delete'>
                            <i class="bi bi-trash3"></i>
                            Eliminar
                        </button>
                    </form>
                </div>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
  @endsection



  <script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
        $('.show-alert').click(function(event) {
            var form = $(this).closest("form");
            var name = $(this).data("name");
            event.preventDefault();
            Swal.fire({
                title: '¿Seguro de que deseas eliminarlo?',
                text: "este proceso puede ser reversible!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed)
                    form.submit(); {}
            })
        });
    });
</script>


