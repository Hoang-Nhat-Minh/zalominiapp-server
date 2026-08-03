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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->enum('category', ['environment', 'urban_order', 'traffic', 'infrastructure', 'electricity_water']);
            $table->string('title');
            $table->text('description');
            $table->json('images')->nullable();
            $table->string('address')->nullable();
            $table->enum('status', ['pending', 'processing', 'resolved', 'rejected'])->default('pending');
            $table->text('officer_note')->nullable();
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
        Schema::dropIfExists('reports');
    }
};
