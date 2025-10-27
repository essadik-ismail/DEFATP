<!-- Top Bar -->
<div class="top-bar">
    <div class="top-bar-content">
        <div class="top-bar-left">
            <div class="breadcrumbs">
                <div class="breadcrumb-item">
                    <i class="fas fa-home"></i>
                    <span>Accueil</span>
                </div>
                @if(request()->routeIs('dashboard'))
                    <!-- Already on dashboard -->
                @elseif(request()->routeIs('articles.*'))
                    <span class="breadcrumb-separator">/</span>
                    <span class="breadcrumb-item">Articles</span>
                    @if(request()->routeIs('articles.create'))
                        <span class="breadcrumb-separator">/</span>
                        <span class="breadcrumb-item">Nouvel Article</span>
                    @elseif(request()->routeIs('articles.edit'))
                        <span class="breadcrumb-separator">/</span>
                        <span class="breadcrumb-item">Modifier Article</span>
                    @endif
                @elseif(request()->routeIs('settings.*'))
                    <span class="breadcrumb-separator">/</span>
                    <span class="breadcrumb-item">Paramètres</span>
                    @if(request()->routeIs('settings.essences'))
                        <span class="breadcrumb-separator">/</span>
                        <span class="breadcrumb-item">Essences</span>
                    @elseif(request()->routeIs('settings.forets'))
                        <span class="breadcrumb-separator">/</span>
                        <span class="breadcrumb-item">Forêts</span>
                    @elseif(request()->routeIs('settings.nature-de-coupes'))
                        <span class="breadcrumb-separator">/</span>
                        <span class="breadcrumb-item">Nature de Coupes</span>
                    @elseif(request()->routeIs('settings.situation-administratives'))
                        <span class="breadcrumb-separator">/</span>
                        <span class="breadcrumb-item">Situations Administratives</span>
                    @elseif(request()->routeIs('exploitants'))
                        <span class="breadcrumb-separator">/</span>
                        <span class="breadcrumb-item">Exploitants</span>
                    @elseif(request()->routeIs('settings.localisations'))
                        <span class="breadcrumb-separator">/</span>
                        <span class="breadcrumb-item">Localisations</span>
                    @endif
                @elseif(request()->routeIs('reports.*'))
                    <span class="breadcrumb-separator">/</span>
                    <span class="breadcrumb-item">Rapports</span>
                    @if(request()->routeIs('reports.articles-by-year'))
                        <span class="breadcrumb-separator">/</span>
                        <span class="breadcrumb-item">Volume par Année</span>
                    @elseif(request()->routeIs('reports.articles-by-foret'))
                        <span class="breadcrumb-separator">/</span>
                        <span class="breadcrumb-item">Volume par Forêt</span>
                    @elseif(request()->routeIs('reports.articles-by-essence'))
                        <span class="breadcrumb-separator">/</span>
                        <span class="breadcrumb-item">Volume par Essence</span>
                    @endif
                @elseif(request()->routeIs('auth.users.*'))
                    <span class="breadcrumb-separator">/</span>
                    <span class="breadcrumb-item">Utilisateurs</span>
                    @if(request()->routeIs('auth.users.create'))
                        <span class="breadcrumb-separator">/</span>
                        <span class="breadcrumb-item">Nouvel Utilisateur</span>
                    @elseif(request()->routeIs('auth.users.edit'))
                        <span class="breadcrumb-separator">/</span>
                        <span class="breadcrumb-item">Modifier Utilisateur</span>
                    @endif
                @elseif(request()->routeIs('auth.profile'))
                    <span class="breadcrumb-separator">/</span>
                    <span class="breadcrumb-item">Mon Profil</span>
                @elseif(request()->routeIs('excel.*'))
                    <span class="breadcrumb-separator">/</span>
                    <span class="breadcrumb-item">Import/Export Excel</span>
                @endif
            </div>
        </div>
        <div class="top-bar-right">
            <div class="top-bar-actions">
                <!-- Notifications -->
                <div class="notification-dropdown">
                    <button class="top-bar-btn notification-btn" onclick="toggleNotifications()" title="Notifications">
                        <i class="fas fa-bell"></i>
                        <span class="notification-badge">3</span>
                    </button>
                    <div class="notification-panel" id="notificationPanel">
                        <div class="notification-header">
                            <h6>Notifications</h6>
                            <button class="mark-all-read" onclick="markAllAsRead()">Tout marquer comme lu</button>
                        </div>
                        <div class="notification-list">
                            <div class="notification-item unread">
                                <div class="notification-icon">
                                    <i class="fas fa-file-alt text-primary"></i>
                                </div>
                                <div class="notification-content">
                                    <div class="notification-title">Nouvel article créé</div>
                                    <div class="notification-text">Article #123 a été ajouté avec succès</div>
                                    <div class="notification-time">Il y a 5 minutes</div>
                                </div>
                            </div>
                            <div class="notification-item unread">
                                <div class="notification-icon">
                                    <i class="fas fa-user-plus text-success"></i>
                                </div>
                                <div class="notification-content">
                                    <div class="notification-title">Nouvel utilisateur</div>
                                    <div class="notification-text">Ahmed Benali a rejoint l'équipe</div>
                                    <div class="notification-time">Il y a 1 heure</div>
                                </div>
                            </div>
                            <div class="notification-item">
                                <div class="notification-icon">
                                    <i class="fas fa-exclamation-triangle text-warning"></i>
                                </div>
                                <div class="notification-content">
                                    <div class="notification-title">Maintenance prévue</div>
                                    <div class="notification-text">Maintenance système prévue ce soir</div>
                                    <div class="notification-time">Il y a 2 heures</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Dark Mode Toggle -->
                <button class="top-bar-btn dark-mode-btn" onclick="toggleDarkMode()" title="Basculer le mode sombre">
                    <i class="fas fa-moon" id="top-dark-mode-icon"></i>
                </button>

                <!-- Profile Dropdown -->
                <div class="profile-dropdown">
                    <button class="top-bar-btn profile-btn" onclick="toggleProfile()" title="Mon profil">
                        <div class="profile-avatar">
                            <i class="fas fa-user"></i>
                        </div>
                        <span class="profile-name">{{ auth()->user()->name ?? 'Utilisateur' }}</span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="profile-panel" id="profilePanel">
                        <div class="profile-header">
                            <div class="profile-info">
                                <div class="profile-avatar-large">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="profile-details">
                                    <h6>{{ auth()->user()->name ?? 'Utilisateur' }}</h6>
                                    <span>{{ auth()->user()->email ?? 'email@example.com' }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="profile-menu">
                            <a href="{{ route('auth.profile') }}" class="profile-menu-item">
                                <i class="fas fa-user-cog"></i>
                                <span>Mon Profil</span>
                            </a>
                            <a href="#" class="profile-menu-item">
                                <i class="fas fa-cog"></i>
                                <span>Paramètres</span>
                            </a>
                            <div class="profile-menu-divider"></div>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="profile-menu-item logout-btn">
                                    <i class="fas fa-sign-out-alt"></i>
                                    <span>Déconnexion</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
