@php
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
$containerNav = $containerNav ?? 'container-fluid';
$navbarDetached = ($navbarDetached ?? '');
@endphp

<!-- Navbar -->
@if(isset($navbarDetached) && $navbarDetached == 'navbar-detached')
<nav class="layout-navbar {{$containerNav}} navbar navbar-expand-xl {{$navbarDetached}} align-items-center bg-navbar-theme" id="layout-navbar">
  @endif
  @if(isset($navbarDetached) && $navbarDetached == '')
  <nav class="layout-navbar navbar navbar-expand-xl align-items-center bg-navbar-theme" id="layout-navbar">
    <div class="{{$containerNav}}">
      @endif

      <!--  Brand demo (display only for navbar-full and hide on below xl) -->
      @if(isset($navbarFull))
      <div class="navbar-brand app-brand demo d-none d-xl-flex py-0 me-4">
        <a href="{{url('/')}}" class="app-brand-link gap-2">
          <span class="app-brand-logo demo">@include('_partials.macros',["width"=>25,"withbg"=>'var(--bs-primary)'])</span>
          <span class="app-brand-text demo menu-text fw-bold text-heading">{{config('variables.templateName')}}</span>
        </a>
      </div>
      @endif

      <!-- ! Not required for layout-without-menu -->
      @if(!isset($navbarHideToggle))
      <div class="layout-menu-toggle navbar-nav align-items-xl-center me-4 me-xl-0{{ isset($menuHorizontal) ? ' d-xl-none ' : '' }} {{ isset($contentNavbar) ?' d-xl-none ' : '' }}">
        <a class="nav-item nav-link px-0 me-xl-6" href="javascript:void(0)">
          <i class="bx bx-menu bx-md"></i>
        </a>
      </div>
      @endif

      <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        <!-- Search -->
        <div class="navbar-nav align-items-center">
          <div class="nav-item d-flex align-items-center">
            <!--i class="bx bx-search bx-md"></i>
          <input type="text" class="form-control border-0 shadow-none ps-1 ps-sm-2" placeholder="Search..." aria-label="Search..."-->
          </div>
        </div>
        <!-- /Search -->
        <ul class="navbar-nav flex-row align-items-center ms-auto">

          <!-- Place this tag where you want the button to render. -->
          <!--li class="nav-item lh-1 me-4">
            <a class="github-button" href="{{config('variables.repository')}}" data-icon="octicon-star" data-size="large" data-show-count="true" aria-label="Star themeselection/sneat-html-laravel-admin-template-free on GitHub">Star</a>
          </li-->

          <!-- Notifications -->
          @php
          $user = Auth::user();
          $notifications = $user->systemNotifications()->take(5)->get();
          $unreadCount = $user->systemNotifications()->whereNull('read_at')->count();
          @endphp

          <li class="nav-item dropdown me-4">
            <a class="nav-link dropdown-toggle position-relative" href="#" data-bs-toggle="dropdown">
              <i class="bx bx-bell bx-md"></i>
              @if($unreadCount > 0)
              <span class="badge rounded-pill bg-danger position-absolute top-0 start-100 translate-middle">
                {{ $unreadCount }}
              </span>
              @endif
            </a>
            <ul class="dropdown-menu dropdown-menu-end" style="width: 300px;">
              @forelse($notifications as $notification)
              <li>
                <form method="POST" action="{{ route('notifications.read', $notification->id) }}">
                  @csrf
                  <button type="submit" class="dropdown-item d-flex flex-column text-start {{ is_null($notification->read_at) ? 'fw-bold' : '' }}">
                    <span>{{ $notification->title }}</span>
                    <small class="text-muted">{{ \Illuminate\Support\Str::limit($notification->message, 50) }}</small>
                    <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                  </button>
                </form>
              </li>
              <li>
                <hr class="dropdown-divider">
              </li>
              @empty
              <li class="dropdown-item text-center text-muted">No hay notificaciones</li>
              @endforelse
              <!--li>
                <form method="POST" action="{{ route('notifications.readAll') }}">
                  @csrf
                  <button class="dropdown-item text-center">Marcar todas como leídas</button>
                </form>
              </li-->
              <li>
                <a class="dropdown-item text-center" href="{{ route('notifications.index') }}">Ver todas</a>
              </li>
            </ul>
          </li>
          <!-- Notifications -->

          <!-- Flag -->
          <li class="nav-item lh-1 me-4">
            <div class="user-info">
              <!--span>{{ auth()->user()->country }}</span-->
              @if(auth()->user()->country)
              <img src="{{ auth()->user()->domain->icon }}" alt="Icono país"
                style="width:40px; height:40px; border-radius:50%;">
              @endif
            </div>
          </li>
          <!-- Flag -->

          <!-- User -->
          <li class="nav-item navbar-dropdown dropdown-user dropdown">
            <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);" data-bs-toggle="dropdown">
              <div class="avatar avatar-online">
                <img src="{{ asset('assets/img/avatars/user.png') }}" alt class="w-px-40 h-auto rounded-circle">
              </div>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              <li>
                <a class="dropdown-item" href="javascript:void(0);">
                  <div class="d-flex">
                    <div class="flex-shrink-0 me-3">
                      <div class="avatar avatar-online">
                        <img src="{{ asset('assets/img/avatars/user.png') }}" alt class="w-px-40 h-auto rounded-circle">
                      </div>
                    </div>
                    <div class="flex-grow-1">
                      <div>
                        <small class="mb-0 d-block">Usuario: {{ auth()->user()->email }}</small>
                      </div>
                      <div>
                        <small class="text-muted d-block">Rol: {{ auth()->user()->role?->name }}</small>
                      </div>
                    </div>
                  </div>
                </a>
              </li>
              <li>
                <div class="dropdown-divider my-1"></div>
              </li>
              <li>
                <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                  <i class="bx bx-power-off bx-md me-3"></i><span>Salir del sistema</span>
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                  @csrf
                </form>
              </li>
            </ul>
          </li>
          <!--/ User -->
        </ul>
      </div>

      @if(!isset($navbarDetached))
    </div>
    @endif
  </nav>
  <!-- / Navbar -->