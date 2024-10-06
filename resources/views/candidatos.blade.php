@extends('layout._theme')

@section('styles')
    <style>.paginate_button, .ellipsis { margin-left: 10px !important}</style>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="d-flex align-items-center justify-content-between">
                <h3 class="card-title mb-0">Apuração 2024 <i class="align-middle" data-feather="chevron-right"></i> Lista de Candidatos</h3>
                <!-- Botão para abrir a página de Apuração -->
                <button type="button" class="btn btn-primary text-uppercase" data-toggle="modal" data-target="#votacaoModal">
                    Apuração
                </button>
            </div>
        </div>
        <div class="card-body">
            <table id="candidatosTable" class="table table-striped" style="width:100%">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Partido</th>
                        <th>Número</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@section('scripts')
<script src="{{ asset('assets/js/datatables.js') }}"></script>
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        // DataTable
        $('#candidatosTable').DataTable({
            "dom": "<'d-flex align-items-center justify-content-between' <'me-2'l><'me-2'f>>" + // 'l' e 'f' dentro de um flexbox
             "B" + // Botões (se houver)
             "t" + // Tabela
             "<'d-flex align-items-center justify-content-between' <'mt-2'i><'mt-2'p>>",
            "processing": true,
            "serverSide": true,
            "ajax": "{{ route('candidatos.data') }}", // URL para buscar dados server-side
            "columns": [
                { "data": "nome" },
                { "data": "partido" },
                { "data": "numero" },
            ],
            'language': {
                'sEmptyTable': '<span class="text-gray-500 text-uppercase fw-semibold d-block pt-6 pb-2">Nenhum Registro encontrado.</span>',
                'sInfo': '<span class="text-gray-800 text-special fw-normal">Mostrando _START_ até _END_ de _TOTAL_ Registro(s)</span>',
                'sInfoEmpty': '<span class="text-gray-800 text-special fw-normal">Mostrando 0 até 0 de 0 Registro(s)</span>',
                'sInfoFiltered': '<span class="text-gray-800 text-special fw-normal mb-0">filtrado de _MAX_ Registro(s)</span>',
                'sInfoPostFix': '',
                'sInfoThousands': '',
                'sInfoThousands': '',
                'sLoadingRecords': '',
                'sZeroRecords': '<div class="d-flex items-align-center py-5"><i class="ki-duotone ki-information-5 fs-1 me-3 text-danger"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i><span class="text-gray-700 text-special fw-normal">Não há resultados para os filtros utilizados, refaça sua busca!</span></div>',
                'sSearch': 'Pesquisar',
                'oPaginate': {
                    'sNext': 'Próximo',
                    'sPrevious': 'Anterior',
                    'sFirst': 'Primeiro',
                    'sLast': 'Último'
                },
                'lengthMenu': 'Mostrar _MENU_ Registros p/ Página'
            },
            initComplete: function() {
                // Adiciona classes ao controle de comprimento
                $('#candidatosTable_length select').addClass('form-control form-control-sm');

                // Adiciona classes ao campo de pesquisa
                $('#candidatosTable_filter input').addClass('form-control form-control-sm');
            }
        });
    });
</script>
@endsection