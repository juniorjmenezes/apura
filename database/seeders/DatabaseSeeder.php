<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;
use Database\Seeders\SecaoSeeder;
use Database\Seeders\CandidatoSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //Usuário (eu :))
        $this->call(UserSeeder::class);
        //Seções
        $this->call(SecaoSeeder::class);
        //Candidatos
        $this->call(CandidatoSeeder::class);
    }
}
