<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('officers', function (Blueprint $table) {
            if (!Schema::hasColumn('officers', 'remember_token')) {
                $table->rememberToken()->nullable()->after('password');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('officers', function (Blueprint $table) {
            if (Schema::hasColumn('officers', 'remember_token')) {
                $table->dropRememberToken();
            }
        });
    }
};
