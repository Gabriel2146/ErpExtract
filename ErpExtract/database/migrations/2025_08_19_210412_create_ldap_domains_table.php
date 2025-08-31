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
        Schema::create('ldap_domains', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');           // Ej: KFC Ecuador
            $table->string('domain');         // Ej: kfc.com.ec
            $table->string('host');           // Ej: ad.kfc.com.ec
            $table->string('base_dn', 2000);        // Ej: dc=kfc,dc=com,dc=ec
            $table->string('username')->nullable();       // Usuario bind
            $table->string('password')->nullable();       // Password bind (se puede cifrar)
            $table->string('country', 4);
            $table->string('icon')->nullable();
            $table->timestamps();
        });

        DB::statement("ALTER TABLE ldap_domains ADD DEFAULT NEWID() FOR id");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ldap_domains');
    }
};
