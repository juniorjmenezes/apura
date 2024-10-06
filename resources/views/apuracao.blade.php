@extends('layout._theme')

@section('styles')

@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="d-flex align-items-center justify-content-between">
                <h3 class="card-title mb-0">Apuração 2024 <i class="align-middle" data-feather="chevron-right"></i> Inserir Votos Válidos</h3>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('apuracao.registrar.votos') }}" method="POST">
                @csrf
                <label class="form-label">Seção</label>
                <select id="secoes" class="form-control" name="secao_id">
                    <option value="">Selecione uma Seção...</option>
                    @foreach($secoes as $secao)
                        <option value="{{ $secao->id }}">{{ $secao->secao }} - {{ $secao->bairro }} - {{ $secao->local }}</option>
                    @endforeach
                </select>
                @foreach($candidatos as $candidato)
                    <label class="form-label">{{ $candidato->nome }}</label>
                    <input type="hidden" name="candidato_id[]" value="{{ $candidato->id }}">
                    <input type="number" class="form-control mb-4" name="votos[]" placeholder="Quantidade de Votos">
                @endforeach
                <button type="submit" class="btn btn-primary text-uppercase w-100">Registrar Votos</button>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
const mySelect = document.getElementById('secoes');
    const choices = new Choices(mySelect, {
        searchEnabled: true, // Permite busca
        // Outras opções de configuração
    });
});
</script>
@endsection