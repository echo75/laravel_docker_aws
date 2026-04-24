<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->char('id', 24)->primary();
            $table->string('firstName', 100);
            $table->string('surName', 100);
            $table->string('email', 255)->unique();
            $table->string('salt', 255)->nullable();
            $table->text('hash')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
