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
        Schema::table('posts', function (Blueprint $table) {
            $table->unsignedBigInteger('news_category_id')->nullable()->after('category');
            $table->text('summary')->nullable()->after('title');
            $table->unsignedBigInteger('views_count')->default(0)->after('status');
            $table->boolean('is_featured')->default(false)->after('views_count');

            $table->foreign('news_category_id')
                ->references('id')
                ->on('news_categories')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropForeign(['news_category_id']);
            $table->dropColumn(['news_category_id', 'summary', 'views_count', 'is_featured']);
        });
    }
};
