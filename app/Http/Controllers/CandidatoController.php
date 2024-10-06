<?php

namespace App\Http\Controllers;

use App\Models\Candidato; // Importa o model de Candidato
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;

class CandidatoController extends Controller
{
    /**
     * Exibe a lista de seções em uma tabela.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Retorna a View
        return view('candidatos');
    }

    public function getData()
    {
        // Seleciona Dados
        $candidatos = Candidato::select('nome', 'partido', 'numero', 'foto')->orderByDesc('numero');

        // Retorna para Tabela DataTables
        return DataTables::of($candidatos)
            // Colunas
            ->addColumn('nome', function ($candidato) {
                return $candidato->nome;
            })
            ->addColumn('partido', function ($candidato) {
                return $candidato->partido;
            })
            ->addColumn('numero', function ($candidato) {
                return $candidato->numero;
            })
            // Filtros
            ->filterColumn('nome', function ($query, $keyword) {
                $query->where('nome', 'like', "%{$keyword}%");
            })
            ->filterColumn('partido', function ($query, $keyword) {
                $query->where('partido', 'like', "%{$keyword}%");
            })
            ->filterColumn('numero', function ($query, $keyword) {
                $query->where('numero', 'like', "%{$keyword}%");
            })
        ->make(true);
    }
}
