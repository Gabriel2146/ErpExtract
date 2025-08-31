<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_table_fields', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->uuid('tabla_id'); // coincide con tablas.id
            $table->foreign('tabla_id')->references('id')->on('tablas')->onDelete('cascade');
            $table->string('field_name');
            $table->timestamps();
        });

        DB::statement("ALTER TABLE user_table_fields ADD DEFAULT NEWID() FOR id");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_table_fields');
    }
};
