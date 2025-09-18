<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Fase 1: agregar la columna (con default para evitar nulos)
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'estado')) {
                $table->enum('estado', ['Bloqueado', 'Activo'])
                      ->default('Activo')
                      ->after('email');
            }
        });

        // Fase 2: backfill (ya existe la columna)
        if (Schema::hasColumn('users', 'estado')) {
            DB::table('users')->whereNull('estado')->update(['estado' => 'Activo']);
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'estado')) {
                $table->dropColumn('estado');
            }
        });
    }
};
