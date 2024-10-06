@extends('layout._theme')

@section('styles')
    <style>.paginate_button, .ellipsis { margin-left: 10px !important}</style>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="d-flex align-items-center justify-content-between">
                <h3 class="card-title mb-0">Apuração 2024 <i class="align-middle" data-feather="chevron-right"></i> Lista de Seções Apuradas</h3>
                <!-- Botão para abrir a página de Apuração -->
                <a href="{{ route('apuracao.index') }}" class="btn btn-primary text-uppercase">Apuração</a>
            </div>
        </div>
        <div class="card-body">
            <table id="apuradasTable" class="table table-striped" style="width:100%">
                <thead>
                    <tr id="apuradasTableHeader"></tr>
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
        // Candidatos
        var candidatos = @json($candidatos);

        // Colunas dinâmicas
        var columns = [
            { data: 'secao', name: 'secao' }
        ];

        // Construir o cabeçalho da tabela dinamicamente
        var headerHtml = '<th>Seção</th>'; // Começa com a coluna "Seção"

        candidatos.forEach(function(candidato) {
            // Adiciona cada candidato às colunas e ao cabeçalho
            columns.push({
                data: 'candidatos.' + candidato.id,
                name: 'candidatos.' + candidato.id,
                defaultContent: '0', // Conteúdo padrão quando não há votos
                render: function(data, type, row) {
                    // Retorna um campo de input para edição
                    return '<input type="number" class="form-control form-control-sm votos-input" value="' + (data !== undefined ? data : 0) + '" data-candidato-id="' + candidato.id + '" data-secao-id="' + row.secao_id + '" />';
                }
            });

            // Constrói o cabeçalho para esse candidato
            headerHtml += '<th>' + candidato.nome + '</th>';
        });

        // Adiciona a coluna 'ações' com um botão para salvar as alterações
        headerHtml += '<th><button id="salvarVotos" class="btn btn-info btn-sm text-uppercase">Alterar</button></th>';
        // Adiciona a configuração da coluna 'acoes' no array de colunas
        columns.push({ data: 'editar', name: 'editar', orderable: false, searchable: false });

        // Insere o cabeçalho na tabela
        $('#apuradasTableHeader').html(headerHtml);

        // Inicializa o DataTables
        var table = $('#apuradasTable').DataTable({
            "dom": "<'d-flex align-items-center justify-content-between' <'me-2'l><'me-2'f>>" + 
                "B" + "t" + "<'d-flex align-items-center justify-content-between' <'mt-2'i><'mt-2'p>>",
            "processing": true,
            "serverSide": true,
            "ajax": "{{ route('apuradas.data') }}",
            "columns": columns,
            "language": {
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
                "lengthMenu": 'Mostrar _MENU_ Registros p/ Página',
            },
            initComplete: function() {
                feather.replace();

                // Adiciona classes ao controle de comprimento
                $('#apuradasTable_length select').addClass('form-control form-control-sm');

                // Adiciona classes ao campo de pesquisa
                $('#apuradasTable_filter input').addClass('form-control form-control-sm');
            },
            drawCallback: function() {
                feather.replace();
            }
        });

        // Ação para salvar os votos
        $('#salvarVotos').on('click', function() {
            var votos = [];

            // Percorre todos os inputs e coleta os votos
            $('.votos-input').each(function() {
                var candidatoId = $(this).data('candidato-id');
                var secaoId = $(this).data('secao-id');
                var votosCount = $(this).val();

                votos.push({
                    candidato_id: candidatoId,
                    secao_id: secaoId,
                    votos: votosCount
                });
            });

            // Envia os dados para o servidor via AJAX
            $.ajax({
                url: '{{ route('apuracao.atualizar.votos') }}', // Rota para atualizar os votos
                method: 'POST',
                data: {
                    votos: votos,
                    _token: '{{ csrf_token() }}' // Adiciona o token CSRF
                },
                success: function(response) {
                    if (response.success) {
                        notyf.success(response.success); // Exibe a mensagem de sucesso
                        table.ajax.reload(); // Recarrega a tabela
                    }
                },
                error: function(xhr) {
                    // Verifica se a resposta do servidor contém uma mensagem de erro
                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        notyf.error(xhr.responseJSON.error); // Exibe a mensagem de erro retornada pelo controlador
                    } else {
                        notyf.error('Erro ao salvar os votos.'); // Mensagem genérica
                    }
                }
            });
        });
    });
</script>
@endsection