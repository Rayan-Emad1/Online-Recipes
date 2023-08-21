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
        Schema::create('recipes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('name');
            $table->string('cuisine');
            $table->text('ingredients');
            $table->text('image_url')->nullable();
            $table->timestamps();
        });

        Schema::create('followers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('follower_id'); 
            $table->unsignedBigInteger('following_id'); 
            $table->timestamps();
        });

        Schema::create('likes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('recipe_id');
            $table->timestamps();
        });

        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('recipe_id');
            $table->text('comment_text');
            $table->timestamps();
        });

        Schema::create('shopping_lists', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('recipe_id');
            $table->timestamps();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('recipes');
        Schema::dropIfExists('followers');
        Schema::dropIfExists('likes');
        Schema::dropIfExists('comments');
        Schema::dropIfExists('list_recipes');

    }
};
