<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Secao extends Model
{
    use HasFactory;
    protected $table = 'secoes';
    protected $fillable = [
        'secao',
        'local',
        'endereco',
        'bairro',
        'aptos',
    ];

    public function apuracoes()
    {
        return $this->hasMany(Apuracao::class);
    }
}