<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User; // Certifique-se de que o modelo User está importado

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Junior Menezes', // Nome do usuário
            'email' => 'juniorjmenezes@gmail.com',
            'password' => bcrypt('Gabriel2015'), // Criptografa a senha
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
