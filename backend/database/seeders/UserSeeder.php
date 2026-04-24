<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->upsert([
            [
                'id'        => '69e21a4d8df84efda0b95eb0',
                'firstName' => 'Johan',
                'surName'   => 'Hedman',
                'email'     => 'johan@hedman.de',
                'salt'      => null,
                'hash'      => Hash::make('password'),
            ],
        ], ['id'], ['firstName', 'surName', 'email', 'salt', 'hash']);
    }
}
