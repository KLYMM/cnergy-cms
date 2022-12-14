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
            $table->uuid()->primary();
            $table->enum('is_active', [0, 1])->default(0); 
            $table->foreignId('role_id')->constrained('roles');
            $table->string('email')->unique();
            $table->string('name');
            $table->longText('biography')->nullable();
            $table->longText('profile_image')->nullable();
            $table->rememberToken();
            $table->timestamp('last_logged_in', 0)->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->softDeletes();

            $table->index(['name', 'email']);
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
