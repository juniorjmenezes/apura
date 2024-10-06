<nav id="sidebar" class="sidebar js-sidebar">
    <div class="sidebar-content js-simplebar">
        <a class="sidebar-brand" href="{{ url('/') }}">
            <span class="align-middle">Apuração2024</span>
        </a>

        <ul class="sidebar-nav">
            <li class="sidebar-header text-uppercase mb-4">Menu</li>
            <li class="sidebar-item {{ (Request::route()->getName() == 'painel.index') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ url('/painel') }}">
                    <i class="align-middle" data-feather="grid"></i> <span class="align-middle">Painel</span>
                </a>
            </li>
            <li class="sidebar-item {{ (Request::route()->getName() == 'apuracao.index') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('apuracao.index') }}">
                    <i class="align-middle" data-feather="check-circle"></i> <span class="align-middle">Apuração</span>
                </a>
            </li>
            <li class="sidebar-item {{ (Request::route()->getName() == 'apuradas.index') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('apuradas.index') }}">
                    <i class="align-middle" data-feather="check-circle"></i> <span class="align-middle">Apuradas</span>
                </a>
            </li>
            <li class="sidebar-item {{ (Request::route()->getName() == 'secoes.index') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('secoes.index') }}">
                    <i class="align-middle" data-feather="airplay"></i> <span class="align-middle">Seções</span>
                </a>
            </li>
            <li class="sidebar-item" {{ (Request::route()->getName() == 'candidatos.index') ? 'active' : '' }}>
                <a class="sidebar-link" href="{{ route('candidatos.index') }}">
                    <i class="align-middle" data-feather="user"></i> <span class="align-middle">Candidatos</span>
                </a>
            </li>
        </ul>
    </div>
</nav>