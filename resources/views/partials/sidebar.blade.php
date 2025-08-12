<!-- Sidebar -->
<nav class="sidebar">
    <button class="sidebar-toggle" onclick="toggleSidebarCollapse()">
        <i class="fas fa-chevron-left"></i>
    </button>
    <div class="sidebar-content">
        <div class="logo">
            <i class="fas fa-tree logo-icon"></i>
            <span>SylvaNet</span>
        </div>
        
        <ul class="nav-menu">
            <!-- Dashboard -->
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}" data-title="Tableau de Bord">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Tableau de Bord</span>
                </a>
            </li>

            <!-- Exploitation Régulière -->
            <li class="nav-item has-submenu">
                <a class="nav-link has-submenu {{ request()->routeIs('articles.*') ? 'active' : '' }}" onclick="toggleSubmenu(this)" data-title="Exploitation régulière">
                    <i class="fas fa-industry"></i>
                    <span>Exploitation régulière</span>
                </a>
                <div class="submenu">
                    <a class="submenu-item {{ request()->routeIs('articles.*') ? 'active' : '' }}" href="{{ route('articles.index') }}">
                        <i class="fas fa-list"></i> <span>Articles</span>
                    </a>
                    <a class="submenu-item {{ request()->routeIs('articles.create') ? 'active' : '' }}" href="{{ route('articles.create') }}">
                        <i class="fas fa-plus"></i> <span>Nouvel Article</span>
                    </a>
                    <a class="submenu-item {{ request()->routeIs('settings.essences') ? 'active' : '' }}" href="{{ route('settings.essences') }}">
                        <i class="fas fa-seedling"></i> <span>Essences</span>
                    </a>
                    <a class="submenu-item {{ request()->routeIs('settings.forets') ? 'active' : '' }}" href="{{ route('settings.forets') }}">
                        <i class="fas fa-mountain"></i> <span>Forêts</span>
                    </a>
                    <a class="submenu-item {{ request()->routeIs('settings.nature-de-coupes') ? 'active' : '' }}" href="{{ route('settings.nature-de-coupes') }}">
                        <i class="fas fa-axe"></i> <span>Nature de Coupes</span>
                    </a>
                    <a class="submenu-item {{ request()->routeIs('settings.situation-administratives') ? 'active' : '' }}" href="{{ route('settings.situation-administratives') }}">
                        <i class="fas fa-building"></i> <span>Situations Administratives</span>
                    </a>
                    <a class="submenu-item {{ request()->routeIs('settings.exploitants') ? 'active' : '' }}" href="{{ route('settings.exploitants') }}">
                        <i class="fas fa-user-tie"></i> <span>Exploitants</span>
                    </a>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="#" data-title="Contract De Partenariat">
                    <i class="fas fa-handshake"></i>
                    <span>Contract De Partenariat</span>
                </a>
            </li>

            <li class="nav-item has-submenu">
                <a class="nav-link has-submenu" onclick="toggleSubmenu(this)" data-title="Carte Professionnelle">
                    <i class="fas fa-id-card"></i>
                    <span>Exploitant Forêtier</span>
                </a>
                <div class="submenu">
                    <a class="submenu-item {{ request()->routeIs('settings.exploitants') ? 'active' : '' }}" href="{{ route('settings.exploitants') }}">
                        <i class="fas fa-user-tie"></i> <span>Carte Professionnelle</span>
                    </a>
                 
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="#" data-title="Bois Particulier">
                    <i class="fas fa-tree"></i>
                    <span>Bois Particulier</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}" href="{{ route('reports.index') }}" data-title="Rapports">
                    <i class="fas fa-chart-line"></i>
                    <span>Rapports</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('excel.*') ? 'active' : '' }}" href="{{ route('excel.index') }}" data-title="Import/Export Excel">
                    <i class="fas fa-file-excel"></i>
                    <span>Import/Export Excel</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('auth.users.*') ? 'active' : '' }}" href="{{ route('auth.users.index') }}" data-title="Gestion des Utilisateurs">
                    <i class="fas fa-users-cog"></i>
                    <span>Gestion des Utilisateurs</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}" href="{{ route('settings.index') }}" data-title="Paramètres">
                    <i class="fas fa-cog"></i>
                    <span>Paramètres</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- Dark Mode Toggle in Sidebar -->
    <!-- <div class="sidebar-footer">
        <button class="dark-mode-toggle" onclick="toggleDarkMode()" title="Basculer le mode sombre">
            <i class="fas fa-moon" id="dark-mode-icon"></i>
            <span>Mode Sombre</span>
        </button>
    </div> -->
</nav>
