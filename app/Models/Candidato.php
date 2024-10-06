<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidato extends Model
{
    use HasFactory;
    protected $table = 'candidatos';
    protected $fillable = [
        'nome',
        'partido',
        'numero',
    ];
    
    public function apuracoes()
    {
        return $this->hasMany(Apuracao::class);
    }
}