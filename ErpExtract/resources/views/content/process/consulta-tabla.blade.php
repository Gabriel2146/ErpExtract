@extends('layouts/contentNavbarLayout')

@section('title', 'Consulta de Tablas SAP')

@section('content')
<div class="card">
    <div class="card-body">
        <h4 class="card-title mb-4">Consulta de Tablas SAP</h4>

        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <label>Ambiente</label>
                <select id="selectAmbiente" class="form-select">
                    <option value="">-- Seleccione --</option>
                    @foreach($ambientes as $ambiente)
                    <option value="{{ $ambiente->id }}">{{ $ambiente->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label>Tabla</label>
                <select id="selectTabla" class="form-select">
                    <option value="">-- Seleccione --</option>
                    @foreach($tablas as $tabla)
                    <option value="{{ $tabla->id }}">{{ $tabla->codigo }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button id="btnEjecutar" class="btn btn-primary w-100" disabled>
                    <i class="bx bx-run me-1"></i> Ejecutar
                </button>
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button id="btnEjecutarFondo" class="btn btn-primary w-100" disabled data-bs-toggle="modal" data-bs-target="#emailModal">
                    <i class="bx bx-run me-1"></i> Ejecutar de fondo
                </button>
            </div>
        </div>

        <div id="loader" class="text-center my-4" style="display:none;">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
        </div>

        <div class="table-responsive" style="max-height:500px; overflow:auto;">
            <table id="resultadosTable" class="table table-striped table-bordered display nowrap">
                <thead>
                    <tr id="theadResultados">
                        <!-- Se llena dinámicamente -->
                    </tr>
                </thead>
                <tbody id="tbodyResultados">
                    <!-- Se llena dinámicamente -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal para selección de email -->
<div class="modal fade" id="emailModal" tabindex="-1" aria-labelledby="emailModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="emailModalLabel">Seleccionar Email para Exportación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="emailForm">
                    <div class="mb-3">
                        <label for="selectEmail" class="form-label">Email de destino</label>
                        <select id="selectEmail" class="form-select" required>
                            <option value="">-- Seleccione un email --</option>
                            @foreach(\App\Models\Email::all() as $email)
                            <option value="{{ $email->email }}">{{ $email->name }} ({{ $email->email }})</option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btnConfirmarExportacion">Iniciar Exportación</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-script')
<script>
    $(document).ready(function() {
        let $selectAmbiente = $('#selectAmbiente');
        let $selectTabla = $('#selectTabla');
        let $btnEjecutar = $('#btnEjecutar');
        let $btnEjecutarFondo = $('#btnEjecutarFondo');
        let $loader = $('#loader');
        let $thead = $('#theadResultados');
        let $tbody = $('#tbodyResultados');

        // Cuando cambie el ambiente, traer tablas
        /*$selectAmbiente.change(function() {
            let ambienteId = $(this).val();
            $selectTabla.prop('disabled', true).html('<option value="">Cargando...</option>');
            $btnEjecutar.prop('disabled', true);

            if (!ambienteId) {
                $selectTabla.html('<option value="">-- Seleccione --</option>');
                return;
            }

            $.get('/api/tablas-por-ambiente/' + ambienteId, function(data) {
                let options = '<option value="">-- Seleccione --</option>';
                data.forEach(tabla => {
                    options += `<option value="${tabla.id}" data-cds="${tabla.cds}" data-entity-type="${tabla.entity_type}">${tabla.nombre}</option>`;
                });
                $selectTabla.html(options).prop('disabled', false);
            });
        });*/

        // Cuando se selecciona tabla
        $selectTabla.change(function() {
            $btnEjecutar.prop('disabled', !$(this).val());
            $btnEjecutarFondo.prop('disabled', !$(this).val());
        });

        // Ejecutar consulta OData
        $btnEjecutar.click(function() {
            let ambienteId = $selectAmbiente.val();
            let tablaId = $selectTabla.val();
            if (!ambienteId || !tablaId) return;

            $loader.show();
            $thead.empty();
            $tbody.empty();

            $.getJSON('/api/consulta-tabla/' + tablaId, {
                ambienteId: ambienteId,
                top: 0,
                skip: 0
            }, function(response) {
                // Extraer la lista de resultados
                let data = response.data || [];

                if (data.length === 0) {
                    $tbody.append('<tr><td colspan="100%" class="text-center">No hay datos</td></tr>');
                    $loader.hide();
                    return;
                }

                // Cabecera dinámica
                let headers = Object.keys(data[0]);
                let trHead = '';
                headers.forEach(h => trHead += `<th>${h}</th>`);
                $thead.html(trHead);

                // Filas
                data.forEach(row => {
                    let tr = '<tr>';
                    headers.forEach(h => tr += `<td>${row[h]}</td>`);
                    tr += '</tr>';
                    $tbody.append(tr);
                });

                $loader.hide();
            }).fail(function(err) {
                $loader.hide();
                alert('Error al consultar la tabla.');
                console.log(err);
            });
        });

        // Confirmar exportación desde modal
        $('#btnConfirmarExportacion').click(function() {
            let selectedEmail = $('#selectEmail').val();
            if (!selectedEmail) {
                alert('Por favor seleccione un email.');
                return;
            }

            let ambienteId = $selectAmbiente.val();
            let tablaId = $selectTabla.val();
            if (!ambienteId || !tablaId) return;

            // Cerrar modal
            $('#emailModal').modal('hide');

            $loader.show();
            $thead.empty();
            $tbody.empty();

            $.getJSON('/api/exporta-tabla/' + tablaId, {
                ambienteId: ambienteId,
                email: selectedEmail,
                top: 0,
                skip: 0
            }, function(response) {
                // Aquí no esperamos datos, solo confirmación
                $loader.hide();
                if (response.message) {
                    // Mostrar mensaje en pantalla
                    alert(response.message);

                    // Opcional: también mostrar en un div en la página
                    $('#mensajeExportacion').html(`<div class="alert alert-info">${response.message}</div>`);
                } else {
                    alert('El proceso de exportación se ha iniciado.');
                }
            }).fail(function(err) {
                $loader.hide();
                alert('Error al iniciar la exportación.');
                console.log(err);
            });
        });
    });
</script>
@endsection