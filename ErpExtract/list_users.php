<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$users = App\Models\User::all();

echo "Usuarios en la base de datos:\n";
echo "================================\n";

foreach ($users as $user) {
    echo "Nombre: " . $user->name . "\n";
    echo "Email: " . $user->email . "\n";
    echo "Contraseña: (hash encriptado)\n";
    echo "-----------------------------\n";
}

echo "\nCredenciales para login:\n";
echo "Administrador: admin@erpextract.com / admin123\n";
echo "Usuario 1: usuario1@erpextract.com / usuario123\n";
echo "Usuario 2: usuario2@erpextract.com / usuario123\n";
echo "Usuario 3: usuario3@erpextract.com / usuario123\n";
