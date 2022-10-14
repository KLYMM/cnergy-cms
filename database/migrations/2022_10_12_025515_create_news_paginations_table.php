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
        Schema::create('news_paginations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('news_id')->unsigned();
            $table->integer('order_by_no');
            $table->string('title', 255);
            $table->longText('content');
            $table->softDeletes();
            $table->uuid('deleted_by')->nullable();

            $table->foreign('deleted_by')
            ->references('uuid')
            ->on('users')
            ->onCascade('delete');

            $table->foreign('news_id')
            ->references('id')
            ->on('news')
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
        Schema::dropIfExists('news_paginations');
    }
};
