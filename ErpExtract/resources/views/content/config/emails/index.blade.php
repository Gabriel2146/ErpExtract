@extends('layouts/contentNavbarLayout')

@section('title', 'Gestión de Emails')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Emails</h5>
        <a href="{{ route('emails.create') }}" class="btn btn-primary">
            <i class="bx bx-plus me-1"></i> Nuevo Email
        </a>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Fecha de Creación</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($emails as $email)
                        <tr>
                            <td>{{ $email->id }}</td>
                            <td>{{ $email->name }}</td>
                            <td>{{ $email->email }}</td>
                            <td>{{ $email->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <a href="{{ route('emails.edit', $email) }}" class="btn btn-sm btn-warning">
                                    <i class="bx bx-edit"></i> Editar
                                </a>
                                <form action="{{ route('emails.destroy', $email) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Está seguro de eliminar este email?')">
                                        <i class="bx bx-trash"></i> Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No hay emails registrados</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
