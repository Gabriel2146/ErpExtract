<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('proveedores', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('username')->unique();
            $table->string('password');
            $table->string('name')->nullable();
            $table->string('country', 10); // código o nombre del país
            $table->string('country_id')->nullable()->index();;
            $table->string('icon')->nullable(); // URL del icono del país
            $table->string('role_id')->nullable()->index();
            $table->timestamps();
        });

        DB::statement("ALTER TABLE proveedores ADD DEFAULT NEWID() FOR id");
    }

    public function down(): void
    {
        Schema::dropIfExists('proveedores');
    }
};
