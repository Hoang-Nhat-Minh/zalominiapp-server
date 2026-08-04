<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->string('household_type')->default('normal')->after('type'); // normal, poor, near_poor, policy
            $table->decimal('income_per_capita', 12, 2)->nullable()->after('household_type');
            $table->string('housing_status')->nullable()->after('income_per_capita');
            $table->string('household_code')->nullable()->after('housing_status');
            $table->string('event_type')->nullable()->after('household_code'); // birth, death, move_in, move_out, split
        });

        Schema::table('reports', function (Blueprint $table) {
            $table->string('assigned_department')->nullable()->after('category');
            $table->timestamp('resolved_at')->nullable()->after('officer_note');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn(['household_type', 'income_per_capita', 'housing_status', 'household_code', 'event_type']);
        });

        Schema::table('reports', function (Blueprint $table) {
            $table->dropColumn(['assigned_department', 'resolved_at']);
        });
    }
};
