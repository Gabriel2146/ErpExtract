<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\dashboard\Analytics;
use App\Http\Controllers\layouts\WithoutMenu;
use App\Http\Controllers\layouts\WithoutNavbar;
use App\Http\Controllers\layouts\Fluid;
use App\Http\Controllers\layouts\Container;
use App\Http\Controllers\layouts\Blank;
use App\Http\Controllers\pages\AccountSettingsAccount;
use App\Http\Controllers\pages\AccountSettingsNotifications;
use App\Http\Controllers\pages\AccountSettingsConnections;
use App\Http\Controllers\pages\MiscError;
use App\Http\Controllers\pages\MiscUnderMaintenance;
use App\Http\Controllers\authentications\LoginBasic;
use App\Http\Controllers\authentications\RegisterBasic;
use App\Http\Controllers\authentications\ForgotPasswordBasic;
use App\Http\Controllers\cards\CardBasic;
use App\Http\Controllers\user_interface\Accordion;
use App\Http\Controllers\user_interface\Alerts;
use App\Http\Controllers\user_interface\Badges;
use App\Http\Controllers\user_interface\Buttons;
use App\Http\Controllers\user_interface\Carousel;
use App\Http\Controllers\user_interface\Collapse;
use App\Http\Controllers\user_interface\Dropdowns;
use App\Http\Controllers\user_interface\Footer;
use App\Http\Controllers\user_interface\ListGroups;
use App\Http\Controllers\user_interface\Modals;
use App\Http\Controllers\user_interface\Navbar;
use App\Http\Controllers\user_interface\Offcanvas;
use App\Http\Controllers\user_interface\PaginationBreadcrumbs;
use App\Http\Controllers\user_interface\Progress;
use App\Http\Controllers\user_interface\Spinners;
use App\Http\Controllers\user_interface\TabsPills;
use App\Http\Controllers\user_interface\Toasts;
use App\Http\Controllers\user_interface\TooltipsPopovers;
use App\Http\Controllers\user_interface\Typography;
use App\Http\Controllers\extended_ui\PerfectScrollbar;
use App\Http\Controllers\extended_ui\TextDivider;
use App\Http\Controllers\icons\Boxicons;
use App\Http\Controllers\form_elements\BasicInput;
use App\Http\Controllers\form_elements\InputGroups;
use App\Http\Controllers\form_layouts\VerticalForm;
use App\Http\Controllers\form_layouts\HorizontalForm;
use App\Http\Controllers\tables\Basic as TablesBasic;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\TipoProveedorController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\ModuloController;
use App\Http\Controllers\AmbienteController;
use App\Http\Controllers\LdapDomainController;
use App\Http\Controllers\TablaController;
use App\Http\Controllers\TablaCampoController;
use App\Http\Controllers\ConsultaTablaController;
use App\Http\Controllers\CampoConfigController;

// -----------------------------
// Redirigir raíz al login
// -----------------------------
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard-analytics');
    }
    return redirect()->route('login');
});

// -----------------------------
// Dashboard protegido por auth
// -----------------------------
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [Analytics::class, 'index'])->name('dashboard-analytics');
});

// -----------------------------
// Authentication
// -----------------------------
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginBasic::class, 'index'])->name('login');
});

Route::middleware(['auth'])->group(function () {
    // Listar notificaciones
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    // Marcar una notificación como leída al dar clic
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    // Marcar todas como leídas
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');
});

Route::post('/login.submit', [LoginBasic::class, 'authenticate'])->name('login.submit');
Route::get('/login', [LoginBasic::class, 'index'])->name('login');
//Route::get('/auth/register-basic', [RegisterBasic::class, 'index'])->name('auth-register-basic');
Route::get('/auth/forgot-password-basic', [ForgotPasswordBasic::class, 'index'])->name('auth-reset-password-basic');
Route::post('/logout', [LoginBasic::class, 'logout'])->name('logout');


// -----------------------------
// Configuracion
// -----------------------------
Route::middleware(['auth'])->group(function () {
    Route::get('/modulos', [ModuloController::class, 'index'])->name('modulos.index');
    Route::post('/modulos', [ModuloController::class, 'store'])->name('modulos.store');
    Route::put('/modulos/{id}', [ModuloController::class, 'update'])->name('modulos.update');
    Route::delete('/modulos/{id}', [ModuloController::class, 'destroy'])->name('modulos.destroy');
});


// -----------------------------
// Configuración - Ambientes
// -----------------------------
Route::middleware(['auth'])->group(function () {
    Route::get('/ambientes', [AmbienteController::class, 'index'])->name('ambientes.index');
    Route::post('/ambientes', [AmbienteController::class, 'store'])->name('ambientes.store');
    Route::put('/ambientes/{id}', [AmbienteController::class, 'update'])->name('ambientes.update');
    Route::delete('/ambientes/{id}', [AmbienteController::class, 'destroy'])->name('ambientes.destroy');
});

// -----------------------------
// Configuración - LDAP Domains
// -----------------------------
Route::middleware(['auth'])->group(function () {
    Route::get('/ldap-domains', [LdapDomainController::class, 'index'])->name('ldap_domains.index');
    Route::post('/ldap-domains', [LdapDomainController::class, 'store'])->name('ldap_domains.store');
    Route::put('/ldap-domains/{id}', [LdapDomainController::class, 'update'])->name('ldap_domains.update');
    Route::delete('/ldap-domains/{id}', [LdapDomainController::class, 'destroy'])->name('ldap_domains.destroy');
});

// -----------------------------
// Configuración - Tablas
// -----------------------------
Route::middleware(['auth'])->group(function () {
    Route::get('/tablas', [TablaController::class, 'index'])->name('tablas.index');
    Route::post('/tablas', [TablaController::class, 'store'])->name('tablas.store');
    Route::put('/tablas/{id}', [TablaController::class, 'update'])->name('tablas.update');
    Route::delete('/tablas/{id}', [TablaController::class, 'destroy'])->name('tablas.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::post('/tabla-campos', [TablaCampoController::class, 'store'])->name('tabla-campos.store');
    Route::delete('/tabla-campos/{id}', [TablaCampoController::class, 'destroy'])->name('tabla-campos.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/consulta-tabla', [ConsultaTablaController::class, 'index'])->name('consulta-tabla');
    Route::get('/api/tablas-por-ambiente/{ambienteId}', [ConsultaTablaController::class, 'tablasPorAmbiente']);
    Route::get('/api/consulta-tabla/{tablaId}', [ConsultaTablaController::class, 'consultaTabla']);
    Route::get('/api/exporta-tabla/{tablaId}', [ConsultaTablaController::class, 'exportaTabla']);
});

// -----------------------------
// Configuración - Emails
// -----------------------------
Route::middleware(['auth'])->group(function () {
    Route::get('/emails', [App\Http\Controllers\EmailController::class, 'index'])->name('emails.index');
    Route::get('/emails/create', [App\Http\Controllers\EmailController::class, 'create'])->name('emails.create');
    Route::post('/emails', [App\Http\Controllers\EmailController::class, 'store'])->name('emails.store');
    Route::get('/emails/{email}/edit', [App\Http\Controllers\EmailController::class, 'edit'])->name('emails.edit');
    Route::put('/emails/{email}', [App\Http\Controllers\EmailController::class, 'update'])->name('emails.update');
    Route::delete('/emails/{email}', [App\Http\Controllers\EmailController::class, 'destroy'])->name('emails.destroy');
});

Route::get('/campos', [CampoConfigController::class, 'index'])->name('campos');
Route::post('/configuracion-campos/store', [CampoConfigController::class, 'store'])->name('campos.store');
Route::get('/tablas-por-modulo/{moduloId}', [CampoConfigController::class, 'tablasPorModulo']);
Route::get('/campos-por-tabla/{tablaId}', [CampoConfigController::class, 'camposPorTabla']);



// -----------------------------
// Proveedores
// -----------------------------
Route::middleware(['auth'])->group(function () {
    Route::get('/tipoproveedor', [TipoProveedorController::class, 'index'])->name('tipoproveedor');
    Route::post('/tipoproveedor', [TipoProveedorController::class, 'store'])->name('tipoproveedor.store');
    Route::put('/tipoproveedor/{id}', [TipoProveedorController::class, 'update'])->name('tipoproveedor.update');
    Route::delete('/tipoproveedor/{id}', [TipoProveedorController::class, 'destroy'])->name('tipoproveedor.destroy');
});

// -----------------------------
// Ubicaciones
// -----------------------------
Route::middleware(['auth'])->group(function () {
    Route::get('/pais', [CountryController::class, 'index'])->name('pais');
    Route::post('/countries', [CountryController::class, 'store'])->name('countries.store');
    Route::put('/countries/{id}', [CountryController::class, 'update'])->name('countries.update');
    Route::delete('/countries/{id}', [CountryController::class, 'destroy'])->name('countries.destroy');
});


// -----------------------------
// Layout Routes
// -----------------------------
Route::get('/layouts/without-menu', [WithoutMenu::class, 'index'])->name('layouts-without-menu');
Route::get('/layouts/without-navbar', [WithoutNavbar::class, 'index'])->name('layouts-without-navbar');
Route::get('/layouts/fluid', [Fluid::class, 'index'])->name('layouts-fluid');
Route::get('/layouts/container', [Container::class, 'index'])->name('layouts-container');
Route::get('/layouts/blank', [Blank::class, 'index'])->name('layouts-blank');

// -----------------------------
// Pages
// -----------------------------
Route::get('/pages/account-settings-account', [AccountSettingsAccount::class, 'index'])->name('pages-account-settings-account');
Route::get('/pages/account-settings-notifications', [AccountSettingsNotifications::class, 'index'])->name('pages-account-settings-notifications');
Route::get('/pages/account-settings-connections', [AccountSettingsConnections::class, 'index'])->name('pages-account-settings-connections');
Route::get('/pages/misc-error', [MiscError::class, 'index'])->name('pages-misc-error');
Route::get('/pages/misc-under-maintenance', [MiscUnderMaintenance::class, 'index'])->name('pages-misc-under-maintenance');



// -----------------------------
// Cards
// -----------------------------
Route::get('/cards/basic', [CardBasic::class, 'index'])->name('cards-basic');

// -----------------------------
// User Interface
// -----------------------------
Route::get('/ui/accordion', [Accordion::class, 'index'])->name('ui-accordion');
Route::get('/ui/alerts', [Alerts::class, 'index'])->name('ui-alerts');
Route::get('/ui/badges', [Badges::class, 'index'])->name('ui-badges');
Route::get('/ui/buttons', [Buttons::class, 'index'])->name('ui-buttons');
Route::get('/ui/carousel', [Carousel::class, 'index'])->name('ui-carousel');
Route::get('/ui/collapse', [Collapse::class, 'index'])->name('ui-collapse');
Route::get('/ui/dropdowns', [Dropdowns::class, 'index'])->name('ui-dropdowns');
Route::get('/ui/footer', [Footer::class, 'index'])->name('ui-footer');
Route::get('/ui/list-groups', [ListGroups::class, 'index'])->name('ui-list-groups');
Route::get('/ui/modals', [Modals::class, 'index'])->name('ui-modals');
Route::get('/ui/navbar', [Navbar::class, 'index'])->name('ui-navbar');
Route::get('/ui/offcanvas', [Offcanvas::class, 'index'])->name('ui-offcanvas');
Route::get('/ui/pagination-breadcrumbs', [PaginationBreadcrumbs::class, 'index'])->name('ui-pagination-breadcrumbs');
Route::get('/ui/progress', [Progress::class, 'index'])->name('ui-progress');
Route::get('/ui/spinners', [Spinners::class, 'index'])->name('ui-spinners');
Route::get('/ui/tabs-pills', [TabsPills::class, 'index'])->name('ui-tabs-pills');
Route::get('/ui/toasts', [Toasts::class, 'index'])->name('ui-toasts');
Route::get('/ui/tooltips-popovers', [TooltipsPopovers::class, 'index'])->name('ui-tooltips-popovers');
Route::get('/ui/typography', [Typography::class, 'index'])->name('ui-typography');

// -----------------------------
// Extended UI
// -----------------------------
Route::get('/extended/ui-perfect-scrollbar', [PerfectScrollbar::class, 'index'])->name('extended-ui-perfect-scrollbar');
Route::get('/extended/ui-text-divider', [TextDivider::class, 'index'])->name('extended-ui-text-divider');

// -----------------------------
// Icons
// -----------------------------
Route::get('/icons/boxicons', [Boxicons::class, 'index'])->name('icons-boxicons');

// -----------------------------
// Form Elements
// -----------------------------
Route::get('/forms/basic-inputs', [BasicInput::class, 'index'])->name('forms-basic-inputs');
Route::get('/forms/input-groups', [InputGroups::class, 'index'])->name('forms-input-groups');

// -----------------------------
// Form Layouts
// -----------------------------
Route::get('/form/layouts-vertical', [VerticalForm::class, 'index'])->name('form-layouts-vertical');
Route::get('/form/layouts-horizontal', [HorizontalForm::class, 'index'])->name('form-layouts-horizontal');

// -----------------------------
// Tables
// -----------------------------
Route::get('/tables/basic', [TablesBasic::class, 'index'])->name('tables-basic');
