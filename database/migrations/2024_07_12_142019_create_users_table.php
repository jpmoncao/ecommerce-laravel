<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->string('id_user', 36)->primary();
            $table->string('name', 255)->default('user_temp');
            $table->date('born_date')->nullable();
            $table->date('address')->nullable();
            $table->char('cpf_cnpj', 14)->nullable();
            $table->string('email', 255)->unique()->nullable();
            $table->string('password', 255)->nullable();
            $table->dateTime('email_verified_at')->nullable();
            $table->string('remember_token')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
