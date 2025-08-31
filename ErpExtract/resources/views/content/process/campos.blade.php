@extends('layouts/contentNavbarLayout')
@section('title', 'Configuración de Campos')

@section('content')
<div class="card">
    <div class="card-body">
        <h4 class="card-title mb-4">Configuración de Campos por Tabla</h4>

        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <label>Módulo</label>
                <select id="selectModulo" class="form-select">
                    <option value="">-- Seleccione --</option>
                    @foreach($modulos as $modulo)
                    <option value="{{ $modulo->id }}">{{ $modulo->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label>Tabla</label>
                <select id="selectTabla" class="form-select" disabled>
                    <option value="">-- Seleccione --</option>
                </select>
            </div>
        </div>

        <div class="row g-3 mb-4" id="camposContainer" style="display:none;">
            <div class="col-md-12">
                <h5>Campos disponibles</h5>
                <div id="camposList" class="form-check">
                    <!-- Checkboxes generados dinámicamente -->
                </div>
            </div>
        </div>

        <button id="btnGuardarCampos" class="btn btn-primary" disabled>Guardar Configuración</button>
    </div>
</div>
@endsection

@section('page-script')
<script>
    $(document).ready(function() {
        let $selectModulo = $('#selectModulo');
        let $selectTabla = $('#selectTabla');
        let $camposContainer = $('#camposContainer');
        let $camposList = $('#camposList');
        let $btnGuardar = $('#btnGuardarCampos');

        $selectModulo.change(function() {
            let moduloId = $(this).val();
            $selectTabla.prop('disabled', true).html('<option value="">Cargando...</option>');
            $camposContainer.hide();
            $btnGuardar.prop('disabled', true);

            if (!moduloId) {
                $selectTabla.html('<option value="">-- Seleccione --</option>');
                return;
            }

            $.get('/tablas-por-modulo/' + moduloId, function(tablas) {
                let options = '<option value="">-- Seleccione --</option>';
                tablas.forEach(t => {
                    options += `<option value="${t.id}">${t.codigo}</option>`;
                });
                $selectTabla.html(options).prop('disabled', false);
            });
        });

        $selectTabla.change(function() {
            let tablaId = $(this).val();
            $camposList.empty();
            $camposContainer.hide();
            $btnGuardar.prop('disabled', true);

            if (!tablaId) return;

            $.get('/campos-por-tabla/' + tablaId, function(campos) {
                campos.forEach(c => {
                    $camposList.append(`
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="${c.nombre}" id="campo_${c.id}" ${c.checked ? 'checked' : ''}>
                        <label class="form-check-label" for="campo_${c.id}">${c.nombre}</label>
                    </div>
                `);
                });
                $camposContainer.show();
                $btnGuardar.prop('disabled', false);
            });
        });

        $btnGuardar.click(function() {
            let tablaId = $selectTabla.val();
            let fields = [];
            $camposList.find('input:checked').each(function() {
                fields.push($(this).val());
            });

            $.post('/configuracion-campos/store', {
                _token: '{{ csrf_token() }}',
                tabla_id: tablaId,
                fields: fields
            }, function(response) {
                alert(response.message);
            });
        });
    });
</script>
@endsection