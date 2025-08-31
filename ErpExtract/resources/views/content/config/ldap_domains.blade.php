@extends('layouts/contentNavbarLayout')

@section('title', 'Dominios LDAP')

@section('content')
<div class="card">
    <div class="card-body">
        <h4 class="card-title">Dominios LDAP</h4>

        <div class="d-flex justify-content-between mb-3">
            <div></div>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createDomainModal">
                <i class="bx bx-plus-circle me-1"></i> Agregar Dominio
            </button>
        </div>

        <table class="table table-bordered table-striped display nowrap" id="ldapDomainsTable">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Dominio</th>
                    <th>Host</th>
                    <th>Base DN</th>
                    <th>Usuario</th>
                    <th>País</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($domains as $domain)
                <tr>
                    <td>{{ $domain->name }}</td>
                    <td>{{ $domain->domain }}</td>
                    <td>{{ $domain->host }}</td>
                    <td>{{ $domain->base_dn }}</td>
                    <td>{{ $domain->username }}</td>
                    <td>{{ $domain->country }}</td>
                    <td>
                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editDomainModal{{ $domain->id }}">
                            <i class="bx bx-pencil me-1"></i>
                        </button>
                        <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteDomainModal{{ $domain->id }}">
                            <i class="bx bx-trash me-1"></i>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Crear Dominio -->
<div class="modal fade" id="createDomainModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('ldap_domains.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title"><i class="bx bx-plus-circle me-1"></i> Agregar Dominio LDAP</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3"><label>Nombre</label><input type="text" name="name" class="form-control" required></div>
                <div class="mb-3"><label>Dominio</label><input type="text" name="domain" class="form-control" required></div>
                <div class="mb-3"><label>Host</label><input type="text" name="host" class="form-control" required></div>
                <div class="mb-3"><label>Base DN</label><input type="text" name="base_dn" class="form-control" required></div>
                <div class="mb-3"><label>Usuario</label><input type="text" name="username" class="form-control"></div>
                <div class="mb-3"><label>Password</label><input type="password" name="password" class="form-control"></div>
                <div class="mb-3"><label>País</label><input type="text" name="country" maxlength="4" class="form-control" required></div>
                <div class="mb-3"><label>Icono</label><input type="text" name="icon" class="form-control"></div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary me-2"><i class="bx bx-check-circle me-1"></i> Guardar</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bx bx-x-circle me-1"></i> Cancelar</button>
            </div>
        </form>
    </div>
</div>

<!-- Modales Editar y Eliminar -->
@foreach($domains as $domain)
<!-- Editar -->
<div class="modal fade" id="editDomainModal{{ $domain->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('ldap_domains.update', $domain->id) }}" method="POST" class="modal-content">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title"><i class="bx bx-pencil-square me-1"></i> Editar Dominio LDAP</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3"><label>Nombre</label><input type="text" name="name" value="{{ $domain->name }}" class="form-control" required></div>
                <div class="mb-3"><label>Dominio</label><input type="text" name="domain" value="{{ $domain->domain }}" class="form-control" required></div>
                <div class="mb-3"><label>Host</label><input type="text" name="host" value="{{ $domain->host }}" class="form-control" required></div>
                <div class="mb-3"><label>Base DN</label><input type="text" name="base_dn" value="{{ $domain->base_dn }}" class="form-control" required></div>
                <div class="mb-3"><label>Usuario</label><input type="text" name="username" value="{{ $domain->username }}" class="form-control"></div>
                <div class="mb-3"><label>Password</label><input type="password" name="password" value="{{ $domain->password }}" class="form-control"></div>
                <div class="mb-3"><label>País</label><input type="text" name="country" value="{{ $domain->country }}" maxlength="4" class="form-control" required></div>
                <div class="mb-3"><label>Icono</label><input type="text" name="icon" value="{{ $domain->icon }}" class="form-control"></div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary me-2"><i class="bx bx-check-circle me-1"></i> Guardar cambios</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bx bx-x-circle me-1"></i> Cancelar</button>
            </div>
        </form>
    </div>
</div>

<!-- Eliminar -->
<div class="modal fade" id="deleteDomainModal{{ $domain->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bx bx-trash me-1"></i> Confirmar Eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de eliminar el dominio <strong>{{ $domain->name }}</strong>?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal"><i class="bx bx-x-circle me-1"></i> Cancelar</button>
                <form action="{{ route('ldap_domains.destroy', $domain->id) }}" method="POST">
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
        $('#ldapDomainsTable').DataTable({
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