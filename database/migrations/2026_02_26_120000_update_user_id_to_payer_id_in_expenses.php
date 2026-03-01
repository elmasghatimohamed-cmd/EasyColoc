<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Si la colonne user_id existe encore, la renommer en payer_id
        if (Schema::hasColumn('expenses', 'user_id') && !Schema::hasColumn('expenses', 'payer_id')) {
            Schema::table('expenses', function (Blueprint $table) {
                $table->renameColumn('user_id', 'payer_id');
            });
        }
        
        // Si les deux colonnes existent, copier les données de user_id vers payer_id
        if (Schema::hasColumn('expenses', 'user_id') && Schema::hasColumn('expenses', 'payer_id')) {
            \DB::statement('UPDATE expenses SET payer_id = user_id WHERE payer_id IS NULL');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Si payer_id existe, la renommer en user_id
        if (Schema::hasColumn('expenses', 'payer_id') && !Schema::hasColumn('expenses', 'user_id')) {
            Schema::table('expenses', function (Blueprint $table) {
                $table->renameColumn('payer_id', 'user_id');
            });
        }
    }
};
