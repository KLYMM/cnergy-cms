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
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->enum('is_headline', [0, 1])->default(0);
            $table->enum('is_home_headline', [0, 1])->default(0);
            $table->enum('is_category_headline', [0, 1])->default(0);
            $table->enum('editor_pick', [0, 1])->default(0);
            $table->enum('is_curated', [0, 1])->default(0);
            $table->enum('is_adult_content', [0, 1])->default(0);
            $table->enum('is_verify_age', [0, 1])->default(0);
            $table->enum('is_advertorial', [0, 1])->default(0);
            $table->enum('is_seo', [0, 1])->default(0);
            $table->enum('is_disable_interactions', [0, 1])->default(0);
            $table->enum('is_branded_content', [0, 1])->default(0);
            $table->string('title', 255);
            $table->string('slug', 255);
            $table->longText('content');
            $table->longText('synopsis');
            $table->longText('description');
            $table->enum('types', ['news', 'photonews', 'video']);
            $table->longText('keywords');
            $table->longText('photographers')->nullable();
            $table->longText('reporters')->nullable();
            $table->string('image', 255)->unique()->nullable();
            $table->text('video')->nullable();
            $table->enum('is_published', [0, 1])->default(1);
            $table->timestamp('published_at')->nullable();
            $table->uuid('published_by')->nullable();
            $table->timestamp('created_at', 0)->nullable();
            $table->uuid('created_by');
            $table->timestamp('updated_at', 0)->nullable();
            $table->uuid('updated_by')->nullable();
            $table->softDeletes();
            $table->uuid('deleted_by')->nullable();
            $table->unsignedBigInteger('category_id');
            $table->index(['title', 'slug', 'created_by', 'types', 'published_by']);

            $table->foreign('category_id')
                    ->references('id')
                    ->on('categories');

            $table->foreign('published_by')
                    ->references('uuid')
                    ->on('users')
                    ->onCascade('delete');

            $table->foreign('created_by')
                    ->references('uuid')
                    ->on('users')
                    ->onCascade('delete');

            $table->foreign('updated_by')
                    ->references('uuid')
                    ->on('users')
                    ->onCascade('delete');

            $table->foreign('deleted_by')
                    ->references('uuid')
                    ->on('users')
                    ->onCascade('delete');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('news');
    }
};
