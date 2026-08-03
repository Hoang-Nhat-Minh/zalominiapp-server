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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('full_name')->nullable();
            $table->string('phone', 20)->nullable()->unique();
            $table->string('zalo_id')->nullable()->unique();
            $table->string('avatar')->nullable();
            $table->string('citizen_code', 20)->nullable()->unique();
            $table->text('address')->nullable();
            $table->enum('role', ['citizen', 'officer', 'admin'])->default('citizen');
            $table->boolean('is_verified')->default(false);
            $table->timestamp('last_login_at')->nullable();
            $table->string('remember_token')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
