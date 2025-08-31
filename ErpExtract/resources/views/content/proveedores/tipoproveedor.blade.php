@extends('layouts/contentNavbarLayout')

@section('title', 'Tipos de Proveedor')

{{-- ✅ Estilos DataTables --}}
@section('vendor-style')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
@endsection

{{-- ✅ Scripts DataTables --}}
@section('vendor-script')
<!-- ✅ jQuery debe ir primero -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- ✅ DataTables + Bootstrap 5 -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<!-- ✅ Extensiones de botones -->
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.colVis.min.js"></script>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <h4 class="card-title mb-4">Tipos de Proveedor</h4>

        <div class="mb-3 text-end">
            <a href="{{ route('tipoproveedor.create') }}" class="btn btn-success">
                <i class="bx bx-plus-circle me-1"></i> Agregar Tipo de Proveedor
            </a>
        </div>

        <div class="table-responsive">
            <table id="tiposTable" class="table table-striped table-bordered nowrap" style="width: 100%;">
                <thead>
                    <tr>
                        <th>Descripción</th>
                        <th>País</th>
                        <th>Clase Interlocutor</th>
                        <th>Socio Comercial</th>
                        <th>Cuenta Contable</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tipos as $tipo)
                    <tr>
                        <td>{{ $tipo->descripcion }}</td>
                        <td>{{ $tipo->countryRelation->name ?? '-' }}</td>
                        <td>{{ $tipo->claseInterlocutor }}</td>
                        <td>{{ $tipo->socioComercial }}</td>
                        <td>{{ $tipo->cuentaContable }}</td>
                        <td>{{ $tipo->estado }}</td>
                        <td>
                            <a href="{{ route('tipoproveedor.edit', $tipo->id) }}" class="btn btn-sm btn-primary">Editar</a>
                            <form action="{{ route('tipoproveedor.destroy', $tipo->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro?')">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

{{-- ✅ Script de inicialización DataTable --}}
@section('page-script')
<script>
    $(document).ready(function() {
        $('#tiposTable').DataTable({
            responsive: true,
            pageLength: 10,
            language: {
                url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
            },
            dom: '<"row mb-3"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 text-end"B>>' +
                '<"row"<"col-sm-12"tr>>' +
                '<"row mt-3"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
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