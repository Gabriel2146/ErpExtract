@extends('layouts/contentNavbarLayout')

@section('title', 'Ambientes')

@section('content')
<div class="card">
    <div class="card-body">
        <h4 class="card-title">Ambientes</h4>

        <div class="d-flex justify-content-between mb-3">
            <div></div>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createAmbienteModal">
                <i class="bx bx-plus-circle me-1"></i> Agregar Ambiente
            </button>
        </div>

        <table class="table table-bordered table-striped display nowrap" id="ambientesTable">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Servidor</th>
                    <th>Ruta</th>
                    <th>Usuario</th>
                    <th>Creado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ambientes as $ambiente)
                <tr>
                    <td>{{ $ambiente->nombre }}</td>
                    <td>{{ $ambiente->servidor }}</td>
                    <td>{{ $ambiente->ruta }}</td>
                    <td>{{ $ambiente->usuario }}</td>
                    <td>{{ $ambiente->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editAmbienteModal{{ $ambiente->id }}">
                            <i class="bx bx-pencil me-1"></i>
                        </button>
                        <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAmbienteModal{{ $ambiente->id }}">
                            <i class="bx bx-trash me-1"></i>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Crear Ambiente -->
<div class="modal fade" id="createAmbienteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('ambientes.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title"><i class="bx bx-plus-circle me-1"></i> Agregar Ambiente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label>Nombre</label>
                    <input type="text" name="nombre" class="form-control" required autofocus>
                </div>
                <div class="mb-3">
                    <label>Servidor</label>
                    <input type="text" name="servidor" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Ruta</label>
                    <input type="text" name="ruta" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Usuario</label>
                    <input type="text" name="usuario" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Clave</label>
                    <input type="password" name="clave" class="form-control" required>
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
@foreach($ambientes as $ambiente)
<!-- Editar -->
<div class="modal fade" id="editAmbienteModal{{ $ambiente->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('ambientes.update', $ambiente->id) }}" method="POST" class="modal-content">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title"><i class="bx bx-pencil-square me-1"></i> Editar Ambiente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label>Nombre</label>
                    <input type="text" name="nombre" class="form-control" value="{{ $ambiente->nombre }}" required>
                </div>
                <div class="mb-3">
                    <label>Servidor</label>
                    <input type="text" name="servidor" class="form-control" value="{{ $ambiente->servidor }}" required>
                </div>
                <div class="mb-3">
                    <label>Ruta</label>
                    <input type="text" name="ruta" class="form-control" value="{{ $ambiente->ruta }}" required>
                </div>
                <div class="mb-3">
                    <label>Usuario</label>
                    <input type="text" name="usuario" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Clave</label>
                    <input type="password" name="clave" class="form-control" required>
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
<div class="modal fade" id="deleteAmbienteModal{{ $ambiente->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bx bx-trash me-1"></i> Confirmar Eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de eliminar el ambiente <strong>{{ $ambiente->nombre }}</strong>?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal"><i class="bx bx-x-circle me-1"></i> Cancelar</button>
                <form action="{{ route('ambientes.destroy', $ambiente->id) }}" method="POST">
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
        $('#ambientesTable').DataTable({
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