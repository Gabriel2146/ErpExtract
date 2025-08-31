<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tablas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('modulo_id');
            $table->string('nombre');
            $table->string('codigo');
            $table->string('cds');
            $table->string('entity_type');
            $table->string('descripcion')->nullable();
            $table->timestamps();

            $table->foreign('modulo_id')->references('id')->on('modulos')->onDelete('cascade');
        });

        DB::statement("ALTER TABLE tablas ADD DEFAULT NEWID() FOR id");
    }

    public function down(): void
    {
        Schema::dropIfExists('tablas');
    }
};
