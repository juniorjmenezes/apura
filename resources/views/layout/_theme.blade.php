<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="Responsive Admin &amp; Dashboard Template based on Bootstrap 5">
	<meta name="author" content="AdminKit">
	<meta name="keywords" content="adminkit, bootstrap, bootstrap 5, admin, dashboard, template, responsive, css, sass, html, theme, front-end, ui kit, web">

	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link rel="shortcut icon" href="img/icons/icon-48x48.png" />

	<link rel="canonical" href="https://demo-basic.adminkit.io/pages-blank.html" />

	<title>Apura2024</title>

	<link href="css/app.css" rel="stylesheet">
	<link href="{{ asset('assets/css/light.css') }}" rel="stylesheet">
	@yield('styles')
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
</head>

<body>
    <div class="wrapper">
		<!--begin::Sidebar-->
        @include('layout.partials.sidebar')
		<!--end::Sidebar-->
		<!--begin::Main-->
        <div class="main">
			<!--begin::Navbar-->
            @include('layout.partials.navbar')
			<!--end::Navbar-->
			<!--begin::Content-->
            <main class="content">
                <div class="container-fluid p-0">
                    @yield('content')
                </div>
            </main>
			<!--end::Main-->
			<!--begin::Footer-->
            @include('layout.partials.footer')
			<!--end::Footer-->
        </div>
		<!--end::Main-->
    </div>

    <!-- begin::Scripts -->
    <script src="{{ asset('assets/js/app.js') }}"></script>
	<script src="https://cdn.jsdelivr.net/npm/notyf/notyf.min.js"></script>
	<script>
		document.addEventListener("DOMContentLoaded", function () {
			// Cria uma nova instância do Notyf com configuração para posição
			const notyf = new Notyf({
				position: {
					x: 'right', // Posição horizontal à direita
					y: 'top'    // Posição vertical no topo
				}
			});
	
			// Verifica se há mensagens de sucesso
			@if(session('success'))
				notyf.success('{{ session('success') }}');
			@endif
	
			// Verifica se há mensagens de erro
			@if($errors->any())
				notyf.error('{{ $errors->first() }}');
			@endif
		});
	</script>	
		
    @yield('scripts')
	<!--end::Scripts-->
</body>

</html>