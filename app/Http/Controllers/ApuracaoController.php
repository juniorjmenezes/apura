<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

use App\Models\Secao;
use App\Models\Candidato;
use App\Models\Apuracao;

class ApuracaoController extends Controller
{
    public function index()
    {
        $secoes = Secao::leftJoin('apuracao', 'secoes.id', '=', 'apuracao.secao_id')
        ->select('secoes.*')
        ->whereNull('apuracao.id') // Verifica se não há apurações
        ->get();
        
        // Recupera Candidatos
        $candidatos = Candidato::all();

        // Retorna para a View
        return view('apuracao', compact('secoes', 'candidatos'));
    }

    public function registrarVotos(Request $request)
    {
        // Validação dos dados recebidos do formulário
        $validatedData = $request->validate([
            'secao_id' => 'required|exists:secoes,id', // Verifica se a seção existe
            'candidato_id' => 'required|array',        // Verifica se o array de candidatos existe
            'candidato_id.*' => 'exists:candidatos,id', // Verifica se cada candidato no array existe
            'votos' => 'required|array',               // Verifica se o array de votos existe
            'votos.*' => 'integer|min:0',              // Valida que cada voto seja um número inteiro e positivo
        ]);
    
        // Recupera a seção para verificar a quantidade de aptos
        $secao = Secao::find($request->secao_id);
    
        // Calcula a soma total dos votos que estão sendo registrados
        $totalVotos = array_sum($request->votos);
    
        // Verifica se o total de votos ultrapassa a quantidade de eleitores aptos
        if ($totalVotos > $secao->aptos) {
            return redirect()->back()->withErrors(['votos' => 'Ultrapassa os ' . $secao->aptos . ' Aptos.']);
        }
    
        // Percorre o array de candidatos e seus votos correspondentes
        foreach ($request->candidato_id as $index => $candidatoId) {
            // Verifica se o número de votos está presente para o candidato atual
            $quantidadeVotos = $request->votos[$index] ?? 0;
    
            // Cria um novo registro de apuração ou atualiza um existente
            Apuracao::updateOrCreate(
                [
                    'secao_id' => $request->secao_id,
                    'candidato_id' => $candidatoId,
                ],
                [
                    'votos' => $quantidadeVotos,
                ]
            );
        }
    
        // Redireciona de volta com uma mensagem de sucesso
        return redirect()->back()->with('success', 'Votos registrados com sucesso!');
    }

    public function apuradas()
    {
        // Recupera Candidatos
        $candidatos = Candidato::all();

        // Retonar para a view
        return view('apuradas', compact('candidatos'));
    }

    public function getData()
    {
        // Obter todas as seções com apurações e candidatos
        $secoes = Secao::with(['apuracoes.candidato'])->get(['id', 'secao', 'local']);
    
        // Obter a lista de candidatos
        $candidatos = Candidato::all();
    
        // Estrutura inicial para os dados
        $data = $secoes->map(function ($secao) use ($candidatos) {       
            $row = [
                'secao' => $secao->secao . ' - ' . $secao->local, // Nome da seção
                'secao_id' => $secao->id, // Adiciona o secao_id,
                'editar' => '' // Adiciona botão de editar
            ];
        
            foreach ($candidatos as $candidato) {
                // Busca a apuração para o candidato atual
                $apuracao = $secao->apuracoes->firstWhere('candidato_id', $candidato->id);
                // Verifica se a apuração foi encontrada e se 'votos' é um inteiro
                $votos = $apuracao ? (int) $apuracao->votos : 0; // Votos ou 0 se não houver
                
                // Cria um campo de entrada para edição
                $row['candidatos.' . $candidato->id] = $votos; // Armazena apenas os votos
            }
        
            return $row;
        });
        
    
        return DataTables::of($data)
            ->rawColumns(array_merge(
                array_map(function ($candidato) {
                    return 'candidatos.' . $candidato;
                }, $candidatos->pluck('id')->toArray()),
                ['editar']
            ))
            ->make(true);
    }
    
    public function atualizarVotos(Request $request)
    {
        // Valida os dados do request
        $validatedData = $request->validate([
            'votos' => 'required|array',
            'votos.*.candidato_id' => 'required|exists:candidatos,id',
            'votos.*.secao_id' => 'required|exists:secoes,id',
            'votos.*.votos' => 'required|integer|min:0',
        ]);
    
        // Cria um array para armazenar o total de votos por seção
        $totalVotosPorSecao = [];
    
        // Loop pelos votos validados
        foreach ($validatedData['votos'] as $voto) {
            // Armazena a soma dos votos por seção
            if (!isset($totalVotosPorSecao[$voto['secao_id']])) {
                $totalVotosPorSecao[$voto['secao_id']] = 0;
            }
            $totalVotosPorSecao[$voto['secao_id']] += $voto['votos'];
    
            // Atualiza ou cria a apuração
            Apuracao::updateOrCreate(
                [
                    'candidato_id' => $voto['candidato_id'],
                    'secao_id' => $voto['secao_id'],
                ],
                [
                    'votos' => $voto['votos'],
                ]
            );
        }
    
        // Verifica se o total de votos ultrapassa a quantidade de eleitores aptos para cada seção
        foreach ($totalVotosPorSecao as $secaoId => $totalVotos) {
            $secao = Secao::findOrFail($secaoId);
    
            if ($totalVotos > $secao->aptos) {
                return response()->json(['error' => 'Ultrapassa os ' . $secao->aptos . ' Aptos'], 422);
            }
        }
    
        return response()->json(['success' => 'Votos atualizados com sucesso!']);
    }    

    public function painel()
    {
        // Recupera Candidatos
        $candidatos = Candidato::all();
        
        // Inicializa variáveis
        $total_votos_apurados = 0; // Total de votos apurados
        $total_votos_invalidos = 0; // Total de votos inválidos
        $total_aptos = 0; // Total de eleitores aptos

        // Recupera Seções
        $secoes = Secao::all();

        // Mapeia a apuração dos votos dos candidatos
        $apuracao = $candidatos->map(function ($candidato) use (&$total_votos_apurados) {
            // Soma os votos para cada candidato
            $candidato->total_votos = $candidato->apuracoes()->sum('votos');
            
            // Adiciona os votos ao total apurado
            $total_votos_apurados += $candidato->total_votos;

            return $candidato;
        });

        // Calcula total de eleitores aptos e votos inválidos
        foreach ($secoes as $secao) {
            $total_votos_validos = $apuracao->sum(function ($candidato) use ($secao) {
                return $candidato->apuracoes()->where('secao_id', $secao->id)->sum('votos');
            });

            // Votos inválidos = Eleitores aptos - Votos válidos
            $votos_invalidos = $secao->aptos - $total_votos_validos;
            $total_votos_invalidos += max(0, $votos_invalidos); // Garante que o número não seja negativo
            $total_aptos += $secao->aptos; // Totaliza os eleitores aptos
        }

        // Calcula Percentual de Votos Inválidos
        $percentual_invalidos = $total_aptos > 0 ? 
            ($total_votos_invalidos / $total_aptos) * 100 : 0;

        // Calcula Percentual sobre Apuração
        $apuracao = $apuracao->map(function ($candidato) use ($total_aptos) {
            // Evita divisão por zero
            $candidato->percentual = $total_aptos > 0 ? 
                ($candidato->total_votos / $total_aptos) * 100 : 0;

            return $candidato;
        });

        // Contar quantas seções têm apurações
        $total_apuradas = $secoes->filter(function ($secao) {
            return !$secao->apuracoes->isEmpty();
        })->count();

        // Calcular o percentual de seções apuradas
        $total_secoes = $secoes->count();
        $percentual_apuradas = $total_secoes > 0 ? ($total_apuradas / $total_secoes) * 100 : 0;

        // Recupera Última Atualização
        if($apuracao->isNotEmpty()) {
            $ultima_atualizacao = Apuracao::latest()->first()->updated_at;
        } else {
            $ultima_atualizacao = '2024-10-06 17:00:00';
        }
        // Ordena os candidatos por total de votos em ordem decrescente
        $apuracao = $apuracao->sortByDesc('total_votos')->values();

        // Verifica se todas as apurações estão concluídas
        $apuracoes_finalizadas = $this->verificaApuracoesConcluidas();

        // Determine o candidato com mais votos, se as apurações estiverem finalizadas
        $vencedor = null;
        if ($apuracoes_finalizadas) {
            $vencedor = $candidatos->sortByDesc('total_votos')->first();
        }

        // Retorna para a View
        return view('painel', compact('candidatos', 'apuracao', 'total_votos_invalidos', 'percentual_invalidos', 'total_apuradas', 'percentual_apuradas', 'ultima_atualizacao', 'apuracoes_finalizadas', 'vencedor'));
    }

    public function checarAtualizacoes()
    {
        // Obtém a última apuração do banco de dados
        $last_update = Apuracao::latest()->first();

        // Verifica se existe uma última atualização
        $last_update_at = $last_update ? $last_update->updated_at : null;

        // Verifica se houve atualizações desde a última verificação armazenada na sessão
        $has_updates = $last_update_at && $last_update_at > session('last_check_time', Carbon::now()->subMinute());

        // Atualiza o tempo da última verificação na sessão
        session(['last_check_time' => Carbon::now()]);

        return response()->json([
            'tem_atualizacao' => $has_updates,
            'ultima_atualizacao' => $last_update_at ? $last_update_at->format('d/m/Y H:i:s') : 'Nenhuma atualização',
        ]);
    }

    public function verificaApuracoesConcluidas()
    {
        // Obter todas as seções
        $secoes = Secao::all();
        
        // Verificar se todas as seções têm pelo menos uma apuração
        foreach ($secoes as $secao) {
            if ($secao->apuracoes->isEmpty()) {
                return false; // Se alguma seção não tiver apurações, retorna false
            }
        }
    
        // Se todas as seções têm apurações, calcular o vencedor
        return $this->calcularVencedor();
    }
    
    public function calcularVencedor()
    {
        // Obter todos os candidatos
        $candidatos = Candidato::all();
    
        // Inicializar a variável para armazenar o vencedor
        $vencedor = null;
        $maiorNumeroDeVotos = 0;
    
        // Iterar por cada candidato e somar seus votos
        foreach ($candidatos as $candidato) {
            // Somar os votos para o candidato, baseado nas apurações
            $totalVotos = Apuracao::where('candidato_id', $candidato->id)->sum('votos');
    
            // Verificar se o candidato atual tem mais votos que o vencedor anterior
            if ($totalVotos > $maiorNumeroDeVotos) {
                $maiorNumeroDeVotos = $totalVotos;
                $vencedor = $candidato;
            }
        }
    
        return $vencedor;
    }
    
}