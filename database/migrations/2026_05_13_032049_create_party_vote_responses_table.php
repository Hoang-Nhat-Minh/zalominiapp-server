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
        Schema::create('party_vote_responses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vote_id');
            $table->unsignedBigInteger('user_id');
            $table->enum('answer', ['agree', 'disagree', 'abstain']);
            $table->text('comment')->nullable();
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
        Schema::dropIfExists('party_vote_responses');
    }
};
