<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Apuracao extends Model
{
    use HasFactory;
    protected $table = 'apuracao';
    protected $fillable = [
        'candidato_id',
        'secao_id',
        'votos',
    ];

    public function candidato()
    {
        return $this->belongsTo(Candidato::class);
    }

    public function secao()
    {
        return $this->belongsTo(Secao::class);
    }

    public function apuracaoCandidatos()
    {
        return $this->hasMany(Apuracao::class, 'secao_id', 'secao_id');
    }
}