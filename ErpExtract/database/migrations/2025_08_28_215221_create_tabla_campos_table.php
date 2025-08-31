<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tabla_campos', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tabla_id');
            $table->string('nombre');
            $table->string('descripcion')->nullable();
            $table->boolean('obligatorio')->default(false);
            $table->timestamps();

            $table->foreign('tabla_id')->references('id')->on('tablas')->onDelete('cascade');
        });

        DB::statement("ALTER TABLE tabla_campos ADD DEFAULT NEWID() FOR id");
    }

    public function down(): void
    {
        Schema::dropIfExists('tabla_campos');
    }
};
