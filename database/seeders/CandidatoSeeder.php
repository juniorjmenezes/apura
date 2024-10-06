<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CandidatoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('candidatos')->insert([
            [
                'nome' => 'Adauto Mendes',
                'partido' => 'MDB',
                'numero' => '12',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nome' => 'Dery Muniz',
                'partido' => 'PSB',
                'numero' => '40',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nome' => 'Erlandson Muniz',
                'partido' => 'PT',
                'numero' => '13',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
