<div class="side_bar">
    <div class="side_header d_flex ai_center jc_center">
        <img src="/assets/logo.png" alt="">
        <h1>PNM Church</h1>

        <i class="fa fa-plus cross"></i>
    </div>
    <ul>
        @can('dashboard.index')
            <li>
                <a href="/dashboard" @class(["active" => route('dashboard.index') == url()->current()])>
                    <i class="fa-solid fa-bars"></i>
                    <span>Dashboard</span>
                </a>
            </li>
        @endcan
        @can('paroissiens.index')
            <li>
                <a href="/paroissiens" class="nav-item {{ Route::is('paroissiens.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-user"></i>
                    <span>Paroissiens</span>
                </a>
            </li>
        @endcan
        @can('association.index')
            <li>
                <a href="/associations" class="nav-item {{ Route::is('association.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-users"></i>
                    <span>Associations</span>
                </a>
            </li>
        @endcan
        @can('engagement.index')
            <li>
                <a href="/engagements"class="nav-item {{ Route::is('engagement.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-hand"></i>
                    <span>Engagements</span>
                </a>
            </li>
        @endcan
        @can('performance.index')
            <li class="submenu hidden {{ Route::is('performance.*') ? 'active' : '' }}">
                <div>
                    <span>
                        <i class="fa-solid fa-pie-chart"></i>
                        <span>Suivi de performance</span>
                    </span>

                    <i class="fa-solid fa-angle-down"></i>
                </div>
                <ul>
                    <li>
                        <a href="/performance/global"class="nav-item {{ Route::is('performance.global') ? 'active' : '' }}">
                            <i class="fa-solid fa-globe"></i>
                            <span>Performance globale</span>
                        </a>
                    </li>
                    <li>
                        <a href="/performance"class="nav-item {{ Route::is('performance.index') ? 'active' : '' }}">
                            <i class="fa-solid fa-person"></i>
                            <span>Performance associations</span>
                        </a>
                    </li>
                </ul>
            </li>
        @endcan
        @can('versement.index')
            <li>
                <a href="/versements"class="nav-item {{ Route::is('versement.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-money-check-dollar"></i>
                    <span>Versements</span>
                </a>
            </li>
        @endcan
        @can('cotisations.index')
            <li>
                <a href="/cotisations"class="nav-item {{ Route::is('cotisations.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-wallet"></i>
                    <span>Cotisations</span>
                </a>
            </li>
        @endcan
        @can('gestionnaire.index')
            <li>
                <a href="/gestionnaire" @class(["active" => route('gestionnaire.index') == url()->current()])>
                    <i class="fa-solid fa-user-tie"></i>
                    <span>Gestionnaires</span>
                </a>
            </li>
        @endcan
        @can('roles.index')
            <li>
                <a href="/roles" @class(["active" => route('roles.index') == url()->current()])>
                    <i class="fa-solid fa-lock"></i>
                    <span>Rôles</span>
                </a>
            </li>
        @endcan
        @can('settings.index')
            <li>
                <a href="/settings" @class(["active" => route('settings.index') == url()->current()])>
                    <i class="fa-solid fa-gear"></i>
                    <span>Paramètres</span>
                </a>
            </li>
        @endcan
    </ul>

    <a href="{{route('auth.logout')}}" class="btn btn_main">
        <i class="fa-solid fa-angle-right"></i>
        <span>Déconnexion</span>
    </a>
</div>
