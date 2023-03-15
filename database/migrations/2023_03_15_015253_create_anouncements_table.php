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
        Schema::create('anouncements', function (Blueprint $table) {
            $table->id();
            $table->string('headline');
            $table->text('message');
            $table->string('targetRole');
            $table->uuid('created_by');
            $table->foreign('created_by')->references('uuid')->on('users');
            $table->timestamp('created_at')->nullable();
            $table->uuid('updated_by');
            $table->foreign('updated_by')->references('uuid')->on('users');
            $table->timestamp('updated_at')->nullable();
            $table->uuid('deleted_by');
            $table->foreign('deleted_by')->references('uuid')->on('users');
            $table->timestamp('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('anouncements');
    }
};