<?php

namespace App\Http\Controllers\authentications;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;         // Usuarios AD locales
use App\Models\Proveedores;  // Tabla de proveedores
use App\Models\LdapDomain;
use LdapRecord\Laravel\Auth\ListensForLdapBindFailure;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;


class LoginBasic extends Controller
{
  use ListensForLdapBindFailure;

  /**
   * Mostrar vista de login
   */
  public function index()
  {
    return view('content.authentications.auth-login-basic');
  }

  /**
   * Autenticar usuario
   */
  public function authenticate(Request $request)
  {
    // 1️⃣ Validar campos obligatorios
    $request->validate([
      'email' => 'required|string',
      'password' => 'required|string',
    ]);

    $email = $request->input('email');
    $password = $request->input('password');

    $defaultRole = Role::where('name', 'Usuario')->first();
    $proveedorRole = Role::where('name', 'Proveedor')->first();

    // 2️⃣ Detectar si es proveedor (sin '@') o usuario AD
    if (strpos($email, '@') === false) {
      // ⚡ Login de Proveedor
      $proveedor = Proveedores::where('email', $email)->first();

      if (!$proveedor || !Hash::check($password, $proveedor->password)) {
        return back()->withErrors(['email' => 'Credenciales inválidas']);
      }

      // ⚡ Loguear en el guard web
      Auth::login($proveedor);
      session(['user_type' => 'proveedor']); // marcar tipo de usuario
      return redirect()->intended('/dashboard');
    }

    // ⚡ Login de usuario AD
    $domainName = explode('@', $email)[1]; // obtener dominio
    $domain = LdapDomain::where('domain', trim($domainName))->first();
    $pais = $domain->country;

    if (!$domain) {
      return back()->withErrors(['email' => 'Dominio no permitido']);
    }

    $ldap = ldap_connect($domain->host);
    if (!$ldap) {
      return back()->withErrors(['username' => 'No se pudo conectar al servidor LDAP']);
    }

    ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
    ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);

    $ous = json_decode($domain->base_dn, true);
    $authenticated = false;

    foreach ($ous as $ou) {
      $dn = "CN={$email},{$ou}";
      $bind = @ldap_bind($ldap, $dn, $password);

      if ($bind) {
        $authenticated = true;
        break;
      }
    }

    /*if (!$authenticated) {
      return back()->withErrors(['username' => 'Usuario o contraseña incorrectos']);
    }

    $bind = @ldap_bind($ldap, $email, $password);

    if (!$bind) {
      return back()->withErrors(['username' => 'Usuario o contraseña incorrectos']);
    }*/
    $country_id = Country::where('code', $domain->country)->first();
    // ⚡ Guardar o actualizar usuario AD local
    $user = User::firstOrCreate(
      ['email' => $email], // busca por email
      [
        'guid' => Str::uuid(), // solo se asigna al crear
        'name' => $email,
        'role_id'  => $defaultRole->id, // GUID del rol "Usuario"
        'country' => $pais,
        'country_id' => $country_id,
      ]
    );

    //log::info($user);
    Auth::login($user);
    /*dd(
      Auth::check(),
      Auth::user(),
      session()->all()
    );*/
    session(['user_type' => 'ad']);
    return redirect('/dashboard');
  }


  /**
   * Cerrar sesión
   */
  public function logout(Request $request)
  {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/login');
  }
}
