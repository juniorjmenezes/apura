<?php

namespace App\Http\Controllers;

use App\Models\Secao; // Importa o model de Secao
use App\Models\Candidato; // Importa o model de Candidato
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;

class SecaoController extends Controller
{
    /**
     * Exibe a lista de seções em uma tabela.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Retorna a View
        $secoes = Secao::with('apuracao')->orderBy('secao');

        return view('secoes', compact('secoes'));
    }

    public function getData()
    {
        // Seleciona Dados e mantém a consulta como um Builder
        $secoes = Secao::with('apuracoes')->orderBy('secao');
    
        // Retorna para Tabela DataTables
        return DataTables::of($secoes)
            // Colunas
            ->addColumn('secao', function ($secao) {
                return $secao->secao;
            })
            ->addColumn('local', function ($secao) {
                return $secao->local;
            })
            ->addColumn('endereco', function ($secao) {
                return $secao->endereco;
            })
            ->addColumn('bairro', function ($secao) {
                return $secao->bairro;
            })
            ->addColumn('aptos', function ($secao) {
                return $secao->aptos;
            })
            ->addColumn('apurada', function ($secao) {
                // Verifica se há apurações associadas à seção
                if ($secao->apuracoes->isNotEmpty()) {
                    return '<i class="align-middle text-success" data-feather="check-circle"></i>';
                } else {
                    return '<i class="align-middle text-danger" data-feather="x-circle"></i>';
                }
            })
            // Filtros
            ->filterColumn('secao', function ($query, $keyword) {
                $query->where('secao', 'like', "%{$keyword}%");
            })
            ->filterColumn('local', function ($query, $keyword) {
                $query->where('local', 'like', "%{$keyword}%");
            })
            ->filterColumn('endereco', function ($query, $keyword) {
                $query->where('endereco', 'like', "%{$keyword}%");
            })
            ->filterColumn('bairro', function ($query, $keyword) {
                $query->where('bairro', 'like', "%{$keyword}%");
            })
            ->filterColumn('aptos', function ($query, $keyword) {
                $query->where('aptos', $keyword);
            })
            ->rawColumns(['apurada'])
            ->make(true);
    }
}
