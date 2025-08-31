<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tipoproveedor', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('descripcion', 30);
            $table->uuid('country')->nullable();
            $table->string('claseInterlocutor', 10)->nullable();
            $table->string('socioComercial', 20)->nullable();
            $table->string('cuentaContable', 25)->nullable();
            $table->integer('estado')->default(1);
            $table->timestamps();
            $table->softDeletes();

            // FK apuntando al schema dbo por defecto
            $table->foreign('country')->references('id')->on('countries')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tipoproveedor');
    }
};
