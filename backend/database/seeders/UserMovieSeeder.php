<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserMovieSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('user_movies')->insertOrIgnore([
            [
                'user_id'  => '69e21a4d8df84efda0b95eb0',
                'movie_id' => '69e21ca0647e1d64809f9d37',
                'type'     => 'watch',
            ],
            [
                'user_id'  => '69e21a4d8df84efda0b95eb0',
                'movie_id' => '69e21e05647e1d64809f9d7b',
                'type'     => 'watched',
            ],
            [
                'user_id'  => '69e21a4d8df84efda0b95eb0',
                'movie_id' => '69e23d50f64b3cbf2dd8ae2e',
                'type'     => 'watched',
            ],
        ]);
    }
}
