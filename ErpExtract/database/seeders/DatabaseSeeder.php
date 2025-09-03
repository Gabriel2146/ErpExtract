<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Country;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   */
  public function run(): void
  {
    // Crear roles si no existen
    $adminRole = Role::firstOrCreate(['name' => 'Admin']);
    $userRole = Role::firstOrCreate(['name' => 'User']);

    // Crear países si no existen
    $country = Country::firstOrCreate(['name' => 'Colombia']);

    // Usuario administrador
    User::create([
      'id' => Str::uuid(),
      'name' => 'Administrador',
      'email' => 'admin@erpextract.com',
      'password' => Hash::make('admin123'),
      'country' => 'CO',
      'country_id' => $country->id,
      'role_id' => $adminRole->id,
    ]);

    // Usuario de prueba 1
    User::create([
      'id' => Str::uuid(),
      'name' => 'Usuario Prueba 1',
      'email' => 'usuario1@erpextract.com',
      'password' => Hash::make('usuario123'),
      'country' => 'CO',
      'country_id' => $country->id,
      'role_id' => $userRole->id,
    ]);

    // Usuario de prueba 2
    User::create([
      'id' => Str::uuid(),
      'name' => 'Usuario Prueba 2',
      'email' => 'usuario2@erpextract.com',
      'password' => Hash::make('usuario123'),
      'country' => 'CO',
      'country_id' => $country->id,
      'role_id' => $userRole->id,
    ]);

    // Usuario de prueba 3
    User::create([
      'id' => Str::uuid(),
      'name' => 'Usuario Prueba 3',
      'email' => 'usuario3@erpextract.com',
      'password' => Hash::make('usuario123'),
      'country' => 'CO',
      'country_id' => $country->id,
      'role_id' => $userRole->id,
    ]);
  }
}
