<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('user_movies', 'review')) {
            Schema::table('user_movies', function (Blueprint $table) {
                $table->text('review')->nullable()->after('type');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('user_movies', 'review')) {
            Schema::table('user_movies', function (Blueprint $table) {
                $table->dropColumn('review');
            });
        }
    }
};
