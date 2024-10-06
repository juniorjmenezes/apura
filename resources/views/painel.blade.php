@extends('layout._theme')

@section('styles')

@endsection

@section('content')
    <div class="row">
        <div class="col-auto d-none d-sm-block">
            <h3><strong>Eleições</strong> 2024</h3>
        </div>
    </div>
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div class="last-updated" id="last-updated">Última Atualização: {{ $ultima_atualizacao->format('d/m/Y H:i:s') }}</div>
        <div class="countdown" id="countdown"></div>
    </div>
    <div class="row">
        @foreach($apuracao as $candidato)
            @php
                $color = [];
                if($candidato->partido == 'MDB') {
                    $color = 'bg-success';
                } elseif ($candidato->partido == 'PSB') {
                    $color = 'bg-warning';
                } elseif ($candidato->partido == 'PT') {
                    $color = 'bg-danger';
                }
            @endphp
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center justify-content-start">
                                <img src="{{ asset($candidato->foto) }}" alt="{{ $candidato->nome }}" class="img-fluid rounded-circle" width="64" height="64">
                                <div class="ms-2">
                                    <div class="d-flex lh-1">
                                        <div class="fs-4 text-dark">{{ $candidato->nome }}</div>
                                        @if($apuracoes_finalizadas && $candidato->id === $vencedor->id)
                                            <span class="badge bg-success text-uppercase ms-2">Eleito</span>
                                        @endif
                                    </div>
                                    <div class="text-muted">{{ $candidato->partido }}/{{ $candidato->numero }}</div>
                                </div>
                            </div>
                            <div class="d-flex flex-column align-items-end lh-1">
                                <div class="fs-1">{{ number_format($candidato->percentual, 2) }}%</div>
                                <div class="fs-4">{{ $candidato->total_votos }} {{ $candidato->total_votos != 1 ? 'votos' : 'voto'}}</div>
                            </div>
                        </div>
                        <div class="progress mt-3">
                            <div class="progress-bar {{ $color }}" 
                                role="progressbar" 
                                style="width: {{ $candidato->percentual }}%;" 
                                aria-valuenow="{{ $candidato->percentual }}" 
                                aria-valuemin="0" 
                                aria-valuemax="100">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="row">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="ms-0">
                            <div class="d-flex lh-1">
                                <div class="fs-4 text-dark">Votos Inválidos</div>
                            </div>
                            <div class="text-muted">Brancos/Nulos/Abstenções</div>
                        </div>
                        <div class="d-flex flex-column align-items-end lh-1">
                            <div class="fs-1">{{ number_format($percentual_invalidos, 2) }}%</div>
                            <div class="fs-4">{{ $total_votos_invalidos }} {{ $total_votos_invalidos != 1 ? 'votos' : 'voto'}}</div>
                        </div>
                    </div>
                    <div class="progress mt-3">
                        <div class="progress-bar bg-secondary" 
                            role="progressbar" 
                            style="width: {{ $percentual_invalidos }}%;" 
                            aria-valuenow="{{ $percentual_invalidos }}" 
                            aria-valuemin="0" 
                            aria-valuemax="100">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="fs-4 text-dark">Votos Válidos</div>
                        <div class="d-flex flex-column align-items-end lh-1">
                            <div class="fs-1">{{ number_format($percentual_validos, 2) }}%</div>
                            <div class="fs-4">{{ $total_votos_validos }} {{ $total_votos_validos != 1 ? 'votos' : 'voto'}}</div>
                        </div>
                    </div>
                    <div class="progress mt-3">
                        <div class="progress-bar bg-primary" 
                            role="progressbar" 
                            style="width: {{ $percentual_validos }}%;" 
                            aria-valuenow="{{ $percentual_validos }}" 
                            aria-valuemin="0" 
                            aria-valuemax="100">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="fs-4 text-dark">Urnas Apuradas</div>
                        <div class="d-flex flex-column align-items-end lh-1">
                            <div class="fs-1">{{ number_format($percentual_apuradas, 2) }}%</div>
                            <div class="fs-4">{{ $total_apuradas }} {{ $total_apuradas != 1 ? 'urnas' : 'urna'}}</div>
                        </div>
                    </div>
                    <div class="progress mt-3">
                        <div class="progress-bar bg-primary" 
                            role="progressbar" 
                            style="width: {{ $percentual_apuradas }}%;" 
                            aria-valuenow="{{ $percentual_apuradas }}" 
                            aria-valuemin="0" 
                            aria-valuemax="100">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    // Elemento onde a última atualização será mostrada
    const lastUpdatedElement = document.getElementById('last-updated');
    const countdownElement = document.getElementById('countdown'); // Elemento para mostrar a contagem regressiva
    let countdownValue = 10; // Inicia a contagem em 10 segundos

    function updateCountdown() {
        countdownElement.innerText = `Possível atualização em: ${countdownValue}`;
        if (countdownValue > 0) {
            countdownValue--;
        }
    }

    function checkForUpdates() {
        fetch('{{ route('painel.checar.atualizacoes') }}') // Rota da API
            .then(response => response.json())
            .then(data => {
                if (data.tem_atualizacao) {
                    location.reload(); // Atualiza a página se houver novas apurações
                } else {
                    lastUpdatedElement.innerText = `Última atualização: ${data.ultima_atualizacao}`; // Atualiza o texto com a nova data/hora
                }
            })
            .catch(error => console.error('Erro ao verificar atualizações:', error));
        
        // Reinicia a contagem
        countdownValue = 10;
    }

    // Atualiza a contagem regressiva a cada segundo
    setInterval(updateCountdown, 1000);

    // Verifica atualizações a cada 10 segundos (10000 ms)
    setInterval(checkForUpdates, 10000);
</script>
@endsection