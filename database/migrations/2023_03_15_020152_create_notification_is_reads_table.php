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
        Schema::create('notification_is_reads', function (Blueprint $table) {
            $table->id();
            $table->uuid('user_id');
            $table->foreign('user_id')->references('uuid')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('anouncement_id');
            $table->foreign('anouncement_id')->references('id')->on('anouncements')->onDelete('cascade')->onUpdate('cascade');
            $table->string('is_read')->nullable();
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
        Schema::dropIfExists('notification_is_reads');
    }
};