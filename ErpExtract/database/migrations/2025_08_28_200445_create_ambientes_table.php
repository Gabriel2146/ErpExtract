<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ambientes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nombre');
            $table->string('servidor');
            $table->string('ruta');
            $table->string('usuario');
            $table->string('clave');
            $table->timestamps();
        });

        DB::statement("ALTER TABLE ambientes ADD DEFAULT NEWID() FOR id");
    }

    public function down(): void
    {
        Schema::dropIfExists('ambientes');
    }
};
