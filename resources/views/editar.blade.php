@extends('layout._theme')

@section('styles')

@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title mb-0">Editar Votos - Seção {{ $secao->nome }}</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('apuracao.editar.votos', $secao->id) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="secao_id" value="{{ $secao->id }}">
                @foreach($candidatos as $candidato)
                    <label class="form-label">{{ $candidato->nome }}</label>
                    <input type="hidden" name="candidato_id[]" value="{{ $candidato->id }}">
                    
                    <!-- Campo oculto para apuracao_id -->
                    <input type="hidden" name="apuracao_id[]" value="{{ $apuracoes->get($candidato->id)->id ?? '' }}">
                    
                    <input type="number" class="form-control mb-4" name="votos[]" 
                        placeholder="Quantidade de Votos" 
                        value="{{ $apuracoes->get($candidato->id)->votos ?? 0 }}"> <!-- Pega o voto se existir -->
                @endforeach
                <button type="submit" class="btn btn-primary text-uppercase w-100">Registrar Votos</button>
            </form>
        </div>
    </div>
@endsection

@section('scripts')

@endsection
