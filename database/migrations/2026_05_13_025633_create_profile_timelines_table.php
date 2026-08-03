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
        Schema::create('profile_timelines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('profile_id');
            $table->enum('status', ['received', 'processing', 'waiting', 'completed', 'rejected']);
            $table->string('title');
            $table->text('note')->nullable();
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
        Schema::dropIfExists('profile_timelines');
    }
};
