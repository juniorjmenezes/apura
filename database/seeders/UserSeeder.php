<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;


class UserSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => 'Junior Menezes', // Nome do usuário
                'email' => 'juniorjmenezes@gmail.com',
                'password' => bcrypt('Gabriel2015'), // Criptografa a senha
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Branco Sousa', // Nome do usuário
                'email' => 'imprimarte@gmail.com',
                'password' => bcrypt('Branco2025'), // Criptografa a senha
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
