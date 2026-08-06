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
        // Fix MySQL/MariaDB strict mode 1067 error caused by legacy '0000-00-00 00:00:00' default value on created_at column
        try {
            DB::statement("SET SESSION sql_mode = ''");
            DB::statement("ALTER TABLE `officers` MODIFY `created_at` timestamp NULL DEFAULT NULL");
            DB::statement("ALTER TABLE `officers` MODIFY `updated_at` timestamp NULL DEFAULT NULL");
            DB::statement("UPDATE `officers` SET `created_at` = NOW() WHERE `created_at` IS NULL OR CAST(`created_at` AS CHAR) = '0000-00-00 00:00:00'");
            DB::statement("UPDATE `officers` SET `updated_at` = NOW() WHERE `updated_at` IS NULL OR CAST(`updated_at` AS CHAR) = '0000-00-00 00:00:00'");
        } catch (\Exception $e) {
            // Ignore if raw SQL statements fail
        }

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
