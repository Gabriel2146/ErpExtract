@extends('layouts/contentNavbarLayout')

@section('title', 'Catálogo de Países')

@section('content')
<div class="card">
    <div class="card-body">
        <h4 class="card-title">Países</h4>

        <div class="d-flex justify-content-between mb-3">
            <div>
                <!-- Opcional: Filtro por rol/pais si es key_user -->
            </div>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createCountryModal">
                <i class="bx bx-plus-circle me-1"></i> Agregar País
            </button>
        </div>

        <table class="table table-bordered table-striped display nowrap" id="countriesTable">
            <thead>
                <tr>
                    <!--th>#</th-->
                    <th>Nombre</th>
                    <th>Código</th>
                    <th>Creado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($countries as $country)
                <tr>
                    <!--td>{{ $loop->iteration }}</td-->
                    <td>{{ $country->name }}</td>
                    <td>{{ $country->code }}</td>
                    <td>{{ $country->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editCountryModal{{ $country->id }}">
                            <i class="bx bx-pencil me-1"></i>
                        </button>
                        <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteCountryModal{{ $country->id }}">
                            <i class="bx bx-trash me-1"></i>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Crear País -->
<div class="modal fade" id="createCountryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('countries.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title"><i class="bx bx-plus-circle me-1"></i> Agregar País</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label>Nombre</label>
                    <input type="text" name="name" class="form-control" required autofocus>
                </div>
                <div class="mb-3">
                    <label>Código</label>
                    <input type="text" name="code" class="form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary me-2"><i class="bx bx-check-circle me-1"></i> Guardar</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bx bx-x-circle me-1"></i> Cancelar</button>
            </div>
        </form>
    </div>
</div>

<!-- Modales Editar y Eliminar -->
@foreach($countries as $country)
<!-- Editar -->
<div class="modal fade" id="editCountryModal{{ $country->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('countries.update', $country->id) }}" method="POST" class="modal-content">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title"><i class="bx bx-pencil-square me-1"></i> Editar País</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label>Nombre</label>
                    <input type="text" name="name" class="form-control" value="{{ $country->name }}" required autofocus>
                </div>
                <div class="mb-3">
                    <label>Código</label>
                    <input type="text" name="code" class="form-control" value="{{ $country->code }}">
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary me-2"><i class="bx bx-check-circle me-1"></i> Guardar cambios</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bx bx-x-circle me-1"></i> Cancelar</button>
            </div>
        </form>
    </div>
</div>

<!-- Eliminar -->
<div class="modal fade" id="deleteCountryModal{{ $country->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bx bx-trash me-1"></i> Confirmar Eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de eliminar <strong>{{ $country->name }}</strong>?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal"><i class="bx bx-x-circle me-1"></i> Cancelar</button>
                <form action="{{ route('countries.destroy', $country->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger"><i class="bx bx-trash me-1"></i> Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection

{{-- ✅ Script de inicialización de DataTables --}}
@section('page-script')
<script>
    $(document).ready(function() {
        $('#countriesTable').DataTable({
            responsive: true,
            pageLength: 10,
            language: {
                url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
            },
            dom: 'Bfrtip',
            buttons: [{
                    extend: 'excelHtml5',
                    text: '<i class="bx bx-file me-1"></i> Excel',
                    className: 'btn btn-success btn-sm'
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="bx bxs-file-pdf me-1"></i> PDF',
                    className: 'btn btn-danger btn-sm'
                },
                {
                    extend: 'print',
                    text: '<i class="bx bx-printer me-1"></i> Imprimir',
                    className: 'btn btn-secondary btn-sm'
                }
            ]
        });
    });
</script>
@endsection