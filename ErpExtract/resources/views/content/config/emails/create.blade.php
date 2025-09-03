@extends('layouts/contentNavbarLayout')

@section('title', 'Crear Email')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Crear Nuevo Email</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('emails.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label">Nombre</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('emails.index') }}" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">Crear Email</button>
            </div>
        </form>
    </div>
</div>
@endsection
