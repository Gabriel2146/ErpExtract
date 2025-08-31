@extends('layouts/blankLayout')

@section('title', 'Login')

@section('page-style')
@vite([
'resources/assets/vendor/scss/pages/page-auth.scss'
])
@endsection

@section('content')
<div class="container-xxl">
  <div class="authentication-wrapper authentication-basic container-p-y">
    <div class="authentication-inner">
      <!-- Login Card -->
      <div class="card px-sm-6 px-0">
        <div class="card-body">
          <!-- Logo -->
          <div class="app-brand justify-content-center">
            <a href="{{ url('/') }}" class="app-brand-link gap-2 d-flex align-items-center">
              {{-- Imagen del logo --}}
              <img src="{{ asset('assets/img/elements/ErpExtract.png') }}" alt="Logo" style="height: 75px;">
              {{-- Texto del logo --}}
              <!--span class="app-brand-text demo text-heading fw-bold">
                {{ config('variables.templateNameShort') }}
              </span-->
            </a>
          </div>
          <!-- /Logo -->

          <h4 class="mb-1">{{ config('variables.templateName') }}! </h4>
          <p class="mb-6">Por favor ingrese los datos requeridos para ingresar al portal.</p>

          <!-- Form -->
          <form id="loginForm" class="mb-6" action="{{ route('login.submit') }}" method="POST">
            @csrf

            <!-- Username / Email -->
            <div class="mb-3">
              <label for="email" class="form-label">Email</label>
              <input type="text" class="form-control" id="email" name="email" placeholder="Ingrese su email" autofocus required>
            </div>

            <!-- Password -->
            <div class="mb-3 form-password-toggle">
              <label class="form-label" for="password">Contraseña</label>
              <div class="input-group input-group-merge">
                <input type="password" id="password" class="form-control" name="password" placeholder="••••••••••••" required />
                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
              </div>
            </div>
            <b>&nbsp;</b>
            <!-- Submit -->
            <div class="mb-3">
              <button class="btn btn-primary d-grid w-100" type="submit">Login</button>
            </div>

            <!-- Display errors -->
            @if ($errors->any())
            <div class="alert alert-danger">
              <ul class="mb-0">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
            @endif
          </form>

        </div>
      </div>
    </div>
  </div>
</div>

<!-- JS Validation -->
@section('page-script')
<script>
  document.getElementById('loginForm').addEventListener('submit', function(e) {
    const email = this.email.value.trim();
    const password = this.password.value.trim();

    if (!email || !password) {
      e.preventDefault();
      alert('Por favor ingrese email y contraseña');
    }
  });
</script>
@endsection

@endsection