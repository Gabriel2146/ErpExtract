@extends('layouts/contentNavbarLayout')

@section('title', 'Catálogo de Tablas')

@section('content')
<div class="card">
    <div class="card-body">
        <h4 class="card-title">Tablas</h4>

        <div class="d-flex justify-content-between mb-3">
            <div></div>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createTableModal">
                <i class="bx bx-plus-circle me-1"></i> Agregar Tabla
            </button>
        </div>

        <table class="table table-bordered table-striped display nowrap" id="tablasTable">
            <thead>
                <tr>
                    <th>Módulo</th>
                    <th>Nombre</th>
                    <th>Código</th>
                    <th>Cds</th>
                    <th>Entity_Type</th>
                    <th>Descripción</th>
                    <th>Creado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tablas as $tabla)
                <tr>
                    <td>{{ $tabla->modulo->nombre ?? '---' }}</td>
                    <td>{{ $tabla->nombre }}</td>
                    <td>{{ $tabla->codigo }}</td>
                    <td>{{ $tabla->cds }}</td>
                    <td>{{ $tabla->entity_type }}</td>
                    <td>{{ $tabla->descripcion }}</td>
                    <td>{{ $tabla->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#detalleCamposModal{{ $tabla->id }}">
                            <i class="bx bx-list-ul me-1"></i> Detalle
                        </button>
                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editTableModal{{ $tabla->id }}">
                            <i class="bx bx-pencil me-1"></i>
                        </button>
                        <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteTableModal{{ $tabla->id }}">
                            <i class="bx bx-trash me-1"></i>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Crear Tabla -->
<div class="modal fade" id="createTableModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('tablas.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title"><i class="bx bx-plus-circle me-1"></i> Agregar Tabla</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label>Módulo</label>
                    <select name="modulo_id" class="form-control" required>
                        <option value="">-- Selecciona --</option>
                        @foreach($modulos as $modulo)
                        <option value="{{ $modulo->id }}">{{ $modulo->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label>Nombre</label>
                    <input type="text" name="nombre" class="form-control" required autofocus>
                </div>
                <div class="mb-3">
                    <label>Código</label>
                    <input type="text" name="codigo" class="form-control">
                </div>
                <div class="mb-3">
                    <label>CDS</label>
                    <input type="text" name="cds" class="form-control">
                </div>
                <div class="mb-3">
                    <label>Entity_Type</label>
                    <input type="text" name="entity_type" class="form-control">
                </div>
                <div class="mb-3">
                    <label>Descripción</label>
                    <textarea name="descripcion" class="form-control"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary me-2"><i class="bx bx-check-circle me-1"></i> Guardar</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bx bx-x-circle me-1"></i> Cancelar</button>
            </div>
        </form>
    </div>
</div>

<!-- Modales Editar / Eliminar / Detalle -->
@foreach($tablas as $tabla)
<!-- Editar -->
<div class="modal fade" id="editTableModal{{ $tabla->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('tablas.update', $tabla->id) }}" method="POST" class="modal-content">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title"><i class="bx bx-pencil-square me-1"></i> Editar Tabla</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label>Módulo</label>
                    <select name="modulo_id" class="form-control" required>
                        @foreach($modulos as $modulo)
                        <option value="{{ $modulo->id }}" {{ $tabla->modulo_id == $modulo->id ? 'selected' : '' }}>
                            {{ $modulo->nombre }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label>Nombre</label>
                    <input type="text" name="nombre" class="form-control" value="{{ $tabla->nombre }}" required>
                </div>
                <div class="mb-3">
                    <label>Código</label>
                    <input type="text" name="codigo" class="form-control" value="{{ $tabla->codigo }}">
                </div>
                <div class="mb-3">
                    <label>CDS</label>
                    <input type="text" name="cds" class="form-control" value="{{ $tabla->cds }}">
                </div>
                <div class="mb-3">
                    <label>Entity_Type</label>
                    <input type="text" name="entity_type" class="form-control" value="{{ $tabla->entity_type }}">
                </div>
                <div class="mb-3">
                    <label>Descripción</label>
                    <textarea name="descripcion" class="form-control">{{ $tabla->descripcion }}</textarea>
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
<div class="modal fade" id="deleteTableModal{{ $tabla->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bx bx-trash me-1"></i> Confirmar Eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de eliminar la tabla <strong>{{ $tabla->nombre }}</strong>?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal"><i class="bx bx-x-circle me-1"></i> Cancelar</button>
                <form action="{{ route('tablas.destroy', $tabla->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger"><i class="bx bx-trash me-1"></i> Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detalle de Campos -->
<div class="modal fade" id="detalleCamposModal{{ $tabla->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bx bx-list-ul me-1"></i> Campos de {{ $tabla->nombre }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">

                <!-- Formulario agregar campo (solo inputs, sin <form>) -->
                <div class="mb-4 agregar-campo" data-tabla="{{ $tabla->id }}">
                    <input type="hidden" class="tabla-id" value="{{ $tabla->id }}">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label">Nombre técnico</label>
                            <input type="text" class="form-control nombre" id="nombreTecnico" placeholder="Ej: MANDT" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Descripción</label>
                            <input type="text" class="form-control descripcion" id="descripcionTecnico" placeholder="Ej: Cliente Mandante">
                        </div>
                        <div class="col-md-2 d-flex align-items-center">
                            <div class="form-check form-switch mt-3">
                                <input class="form-check-input obligatorio" id="obligatorioTecnico" type="checkbox">
                                <label class="form-check-label ms-2">Obligatorio</label>
                            </div>
                        </div>
                        <div class="col-md-2 text-end">
                            <button type="button" id="btn-agregar-campo" class="btn btn-primary w-100 btn-agregar-campo">
                                <i class="bx bx-plus-circle me-1"></i> Agregar
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Tabla de campos existentes -->
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="camposTable{{ $tabla->id }}">
                        <thead class="table-light">
                            <tr>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Obligatorio</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tabla->campos as $campo)
                            <tr>
                                <td>{{ $campo->nombre }}</td>
                                <td>{{ $campo->descripcion }}</td>
                                <td>{{ $campo->obligatorio ? 'Sí' : 'No' }}</td>
                                <td class="text-center">
                                    <button type="button" data-campo="{{ $campo->id }}" class="btn btn-sm btn-danger btn-eliminar-campo">
                                        <i class="bx bx-trash me-1"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>


@endforeach
@endsection

@section('page-script')
<script>
    $(document).ready(function() {
        // DataTable de tablas principal
        /*$('#tablasTable').DataTable({
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

        // Inicializar DataTable en cada tabla de campos
        $('table[id^="camposTable"]').each(function() {
            $(this).DataTable({
                paging: true,
                searching: true,
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
                }
            });
        });*/

        // Agregar campo sin form, usando jQuery
        $("#btn-agregar-campo").click(function() {
            let wrapper = $(this).closest('.agregar-campo');
            let tablaId = wrapper.data('tabla');
            let nombre = $("#nombreTecnico").val();
            let descripcion = $('#descripcionTecnico').val();
            let obligatorio = $('#obligatorioTecnico').is(':checked') ? 1 : 0;

            $.post("{{ route('tabla-campos.store') }}", {
                _token: "{{ csrf_token() }}",
                tabla_id: tablaId,
                nombre: nombre,
                descripcion: descripcion,
                obligatorio: obligatorio
            }, function(resp) {

                let campo = resp.campo;

                let fila = `
                    <tr>
                        <td>${campo.nombre}</td>
                        <td>${campo.descripcion ?? ''}</td>
                        <td>${campo.obligatorio ? 'Sí' : 'No'}</td>
                        <td class="text-center">                    
                            <button type="button" data-campo="${campo.id}" class="btn btn-sm btn-danger btn-eliminar-campo">
                                <i class="bx bx-trash me-1"></i>
                            </button>                    
                        </td>
                    </tr>
                `;
                $("#camposTable" + tablaId + " tbody").append(fila);

                // Resetear inputs
                $('#nombreTecnico').val('');
                $('#descripcionTecnico').val('');
                $('#obligatorioTecnico').prop('checked', false);

                showToast(resp.message, 'success');

            }).fail(function() {
                showToast("Error al guardar el campo", 'error');
            });
        });

        $(document).on('click', '.btn-eliminar-campo', function() {
            let campoId = $(this).data('campo');
            let fila = $(this).closest('tr');

            if (confirm('¿Seguro que deseas eliminar este campo?')) {
                $.ajax({
                    url: '/tabla-campos/' + campoId,
                    type: 'POST', // POST con _method DELETE
                    data: {
                        _token: "{{ csrf_token() }}",
                        _method: 'DELETE'
                    },
                    success: function(resp) {
                        if (resp.success) {
                            fila.remove();
                            showToast(resp.message, 'warning');
                        } else {
                            showToast(resp.message || 'No se pudo eliminar el campo', 'error');
                        }
                    },
                    error: function(xhr) {
                        // si quieres mostrar el mensaje de validación de Laravel
                        let msg = xhr.responseJSON?.message || 'Error al eliminar el campo';
                        showToast(msg, 'error');
                    }
                });
            }
        });

    });
</script>
@endsection