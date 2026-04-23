<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'ANEF — Gestion Forestière')</title>

    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon.svg') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])


    @stack('styles')
</head>

<body>

    {{-- Toast container --}}
    <div class="toast-container" id="toastContainer" aria-live="polite" aria-atomic="false"></div>

    {{-- Sidebar backdrop (mobile) --}}
    <div class="sb-backdrop" id="sbBackdrop" onclick="closeSidebar()"></div>

    <div class="shell">

        {{-- ═══════════ SIDEBAR ═══════════ --}}
        <aside class="sidebar no-print" id="sidebar" aria-label="Navigation principale">

            {{-- Brand --}}
            <div class="sb-brand">
                <div class="sb-brand-mark" aria-hidden="true">
                    <i class="fas fa-tree"></i>
                </div>
                <div class="sb-brand-text">
                    <h1 class="sb-brand-name">DEFATP</h1>
                    <p class="sb-brand-sub">ANEF — Eaux &amp; Forêts Maroc</p>
                </div>
                <button class="sb-close" id="sbCloseBtn" onclick="closeSidebar()" aria-label="Fermer le menu">
                    <i class="fas fa-times" style="font-size:0.625rem;pointer-events:none;"></i>
                </button>
            </div>

            {{-- Navigation --}}
            <nav class="sb-nav" aria-label="Menu principal">

                <div class="sb-section">Navigation</div>

                <div class="sb-item">
                    <a href="{{ route('dashboard') }}"
                       class="sb-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                       aria-current="{{ request()->routeIs('dashboard') ? 'page' : 'false' }}">
                        <span class="sb-icon"><i class="fas fa-th-large"></i></span>
                        <span class="sb-label">Tableau de bord</span>
                    </a>
                </div>

                {{-- Exploitation group --}}
                @php
                    $exploitActive = request()->routeIs('cessions.*','exploitants.*','carnets.*','vehicles.*','workflow.alerts.index');
                @endphp
                <div class="sb-item has-submenu {{ $exploitActive ? 'expanded' : '' }}" id="nav-exploitation">
                    <button type="button" class="sb-group-btn" id="toggle-exploitation"
                            aria-expanded="{{ $exploitActive ? 'true' : 'false' }}"
                            aria-controls="submenu-exploitation">
                        <span class="sb-icon"><i class="fas fa-folder-open"></i></span>
                        <span class="sb-label">Exploitation</span>
                        <i class="fas fa-chevron-right sb-chevron" aria-hidden="true"></i>
                    </button>
                    <div class="sb-submenu {{ $exploitActive ? '' : 'collapsed' }}" id="submenu-exploitation"
                         role="region" aria-label="Sous-menu Exploitation">
                        <a href="{{ route('cessions.index') }}"
                           class="sb-sub-link {{ request()->routeIs('cessions.*') ? 'active' : '' }}">
                            <i class="fas fa-layer-group"></i><span>Cessions</span>
                        </a>
                        <a href="{{ route('carnets.index') }}"
                           class="sb-sub-link {{ request()->routeIs('carnets.*') ? 'active' : '' }}">
                            <i class="fas fa-book"></i><span>Carnets</span>
                        </a>
                        @can('vehicles.declare')
                            <a href="{{ route('vehicles.overview') }}"
                               class="sb-sub-link {{ request()->routeIs('vehicles.*') ? 'active' : '' }}">
                                <i class="fas fa-truck"></i><span>Véhicules</span>
                            </a>
                        @endcan
                        <a href="{{ route('exploitants.index') }}"
                           class="sb-sub-link {{ request()->routeIs('exploitants.*') ? 'active' : '' }}">
                            <i class="fas fa-user-tie"></i><span>Exploitants forestiers</span>
                        </a>
                        <a href="{{ route('workflow.alerts.index') }}"
                           class="sb-sub-link {{ request()->routeIs('workflow.alerts.index') ? 'active' : '' }}">
                            <i class="fas fa-bell"></i><span>Alertes</span>
                            @if(($sidebarAlertCount ?? 0) > 0)
                                <span class="sb-badge">{{ $sidebarAlertCount > 99 ? '99+' : $sidebarAlertCount }}</span>
                            @endif
                        </a>
                    </div>
                </div>

                {{-- Entity data --}}
                <div class="sb-item">
                    <a href="{{ route('entity-data.index') }}"
                       class="sb-link {{ request()->routeIs('entity-data.*','essences.*','forets.*','situations.*','natures.*','vocations.*','coperatives.*','products.*','prestations.*') ? 'active' : '' }}">
                        <span class="sb-icon"><i class="fas fa-database"></i></span>
                        <span class="sb-label">Données des entités</span>
                    </a>
                </div>

                <div class="sb-section">Général</div>

                <div class="sb-item">
                    <a href="{{ route('auth.profile') }}"
                       class="sb-link {{ request()->routeIs('auth.profile') ? 'active' : '' }}">
                        <span class="sb-icon"><i class="fas fa-sliders-h"></i></span>
                        <span class="sb-label">Paramètres</span>
                    </a>
                </div>

                @can('activity_logs.view')
                    <div class="sb-item">
                        <a href="{{ route('activity-logs.index') }}"
                           class="sb-link {{ request()->routeIs('activity-logs.*') ? 'active' : '' }}">
                            <span class="sb-icon"><i class="fas fa-history"></i></span>
                            <span class="sb-label">Journal d'activité</span>
                        </a>
                    </div>
                @endcan

                @canany(['users.view', 'roles.view'])
                    <div class="sb-section">Administration</div>

                    @can('users.view')
                        <div class="sb-item">
                            <a href="{{ route('users.index') }}"
                               class="sb-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                                <span class="sb-icon"><i class="fas fa-users-cog"></i></span>
                                <span class="sb-label">Utilisateurs</span>
                            </a>
                        </div>
                    @endcan

                    @can('roles.view')
                        <div class="sb-item">
                            <a href="{{ route('roles.index') }}"
                               class="sb-link {{ request()->routeIs('roles.*') ? 'active' : '' }}">
                                <span class="sb-icon"><i class="fas fa-shield-alt"></i></span>
                                <span class="sb-label">Rôles &amp; Permissions</span>
                            </a>
                        </div>
                    @endcan
                @endcanany

            </nav>

            {{-- Footer --}}
            <div class="sb-footer">
                <div class="sb-status-strip">
                    <span class="sb-status-dot"></span>
                    <span class="sb-status-text">Session active</span>
                </div>
                <div class="sb-user">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'U') }}&background=163326&color=ffffff&bold=true&size=64"
                         alt="Avatar" class="sb-avatar">
                    <div class="sb-user-info">
                        <div class="sb-user-name">{{ auth()->user()->name ?? 'Utilisateur' }}</div>
                        <div class="sb-user-email">{{ auth()->user()->email ?? 'agent@anef.ma' }}</div>
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="sb-logout" title="Se déconnecter" aria-label="Se déconnecter">
                            <i class="fas fa-sign-out-alt" style="pointer-events:none;"></i>
                        </button>
                    </form>
                </div>
            </div>

        </aside>

        {{-- ═══════════ MAIN ═══════════ --}}
        <div class="main-wrap">

            {{-- Top Header --}}
            <header class="top-header no-print" role="banner">

                <button id="sbHamburger" onclick="toggleSidebar()"
                        aria-label="Ouvrir le menu" aria-controls="sidebar">
                    <i class="fas fa-bars" style="font-size:0.875rem;pointer-events:none;"></i>
                </button>

                <nav class="bc-wrap" aria-label="Fil d'Ariane">
                    <ol class="bc">
                        @if(request()->routeIs('dashboard'))
                            <li class="bc-item active">Tableau de bord</li>
                        @else
                            <li class="bc-item">
                                <a href="{{ route('dashboard') }}">Tableau de bord</a>
                            </li>
                            @yield('breadcrumb')
                        @endif
                    </ol>
                </nav>

                <div class="hdr-right">
                    @auth
                        <x-notifications-dropdown />
                        <div class="hdr-divider" aria-hidden="true"></div>

                        <div class="relative" x-data="{ open: false }" @click.away="open = false">
                            <button @click="open = !open" class="usr-btn" :aria-expanded="open" aria-haspopup="true">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'U') }}&background=163326&color=ffffff&bold=true&size=64"
                                     alt="Profil" class="usr-avatar">
                                <div class="user-info-text">
                                    <span class="usr-name">{{ auth()->user()->name ?? 'Utilisateur' }}</span>
                                    <span class="usr-role">{{ auth()->user()->email ?? '' }}</span>
                                </div>
                                <i class="fas fa-chevron-down"
                                   :class="{ 'rotate-180': open }"
                                   style="font-size:0.4375rem;color:var(--text-muted);margin-left:0.25rem;transition:transform 0.2s;flex-shrink:0;"
                                   aria-hidden="true"></i>
                            </button>

                            <div x-show="open"
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="opacity-0 -translate-y-1 scale-95"
                                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                 x-transition:leave-end="opacity-0 -translate-y-1 scale-95"
                                 class="usr-dropdown" style="display:none;">
                                <div class="usr-dd-header">
                                    <div class="usr-dd-name">{{ auth()->user()->name ?? 'Utilisateur' }}</div>
                                    <div class="usr-dd-email">{{ auth()->user()->email ?? '' }}</div>
                                </div>
                                <div class="usr-dd-body">
                                    <a href="{{ route('auth.profile') }}" class="usr-dd-item">
                                        <i class="fas fa-user-circle"></i>Mon profil
                                    </a>
                                    @can('users.view')
                                        <a href="{{ route('users.index') }}" class="usr-dd-item">
                                            <i class="fas fa-users-cog"></i>Gestion utilisateurs
                                        </a>
                                    @endcan
                                    <a href="{{ route('settings.index') }}" class="usr-dd-item">
                                        <i class="fas fa-cog"></i>Paramètres
                                    </a>
                                    <div class="usr-dd-sep"></div>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="usr-dd-item danger">
                                            <i class="fas fa-sign-out-alt"></i>Se déconnecter
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endauth
                </div>

            </header>

            {{-- Page content --}}
            <main class="content-area" id="main-content" role="main">

                {{-- Session flash messages --}}
                @if(session('success'))
                    <div class="flash-msg flash-success" role="alert">
                        <i class="fas fa-check-circle flex-shrink-0" style="margin-top:0.0625rem;"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif
                @if(session('error'))
                    <div class="flash-msg flash-error" role="alert">
                        <i class="fas fa-exclamation-circle flex-shrink-0" style="margin-top:0.0625rem;"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif
                @if(session('warning'))
                    <div class="flash-msg flash-warning" role="alert">
                        <i class="fas fa-exclamation-triangle flex-shrink-0" style="margin-top:0.0625rem;"></i>
                        <span>{{ session('warning') }}</span>
                    </div>
                @endif
                @if(session('info'))
                    <div class="flash-msg flash-info" role="alert">
                        <i class="fas fa-info-circle flex-shrink-0" style="margin-top:0.0625rem;"></i>
                        <span>{{ session('info') }}</span>
                    </div>
                @endif

                <div class="page-content">
                    @yield('content')
                </div>

            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/modal.js') }}"></script>
    <script src="{{ asset('js/excel-filters.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/animejs@3.2.1/lib/anime.min.js"></script>

    <script>
        /* ── Sidebar toggle ──────────────────────────────────────────── */
        function toggleSidebar() {
            if (window.innerWidth >= 1024) return;
            const sb  = document.getElementById('sidebar');
            const bd  = document.getElementById('sbBackdrop');
            const btn = document.getElementById('sbHamburger');
            const open = sb.classList.toggle('open');
            bd?.classList.toggle('active', open);
            document.body.classList.toggle('sidebar-open', open);
            if (btn) {
                btn.querySelector('i').className = open ? 'fas fa-times' : 'fas fa-bars';
                btn.setAttribute('aria-label', open ? 'Fermer le menu' : 'Ouvrir le menu');
                btn.style.fontSize = '0.875rem';
            }
        }

        function closeSidebar() {
            const sb  = document.getElementById('sidebar');
            const bd  = document.getElementById('sbBackdrop');
            const btn = document.getElementById('sbHamburger');
            sb.classList.remove('open');
            bd?.classList.remove('active');
            document.body.classList.remove('sidebar-open');
            if (btn) {
                btn.querySelector('i').className = 'fas fa-bars';
                btn.setAttribute('aria-label', 'Ouvrir le menu');
            }
        }

        window.addEventListener('resize', () => { if (window.innerWidth >= 1024) closeSidebar(); });
        document.addEventListener('keydown', e => { if (e.key === 'Escape') closeSidebar(); });

        /* ── Exploitation submenu ────────────────────────────────────── */
        document.getElementById('toggle-exploitation')?.addEventListener('click', function () {
            const item    = document.getElementById('nav-exploitation');
            const submenu = document.getElementById('submenu-exploitation');
            if (!item || !submenu) return;
            item.classList.toggle('expanded');
            submenu.classList.toggle('collapsed');
            this.setAttribute('aria-expanded', submenu.classList.contains('collapsed') ? 'false' : 'true');
        });

        /* ── Toast system ────────────────────────────────────────────── */
        window.UXUtils = {
            showToast(message, type = 'info', options = {}) {
                const container = document.getElementById('toastContainer');
                if (!container || !message) return null;
                const cfg = { duration: 5000, title: null, closable: true, dedupe: true, dedupeWindow: 4000, maxVisible: 3, ...options };
                const esc = v => { const d = document.createElement('div'); d.textContent = v == null ? '' : String(v); return d.innerHTML; };
                const icons  = { success: 'fas fa-check-circle', error: 'fas fa-exclamation-circle', warning: 'fas fa-exclamation-triangle', info: 'fas fa-info-circle' };
                const titles = { success: 'Succès', error: 'Erreur', warning: 'Attention', info: 'Information' };
                const icon  = icons[type]  || icons.info;
                const title = cfg.title || titles[type] || titles.info;
                const sig   = [type, title, message].join('|');
                this._history = this._history || new Map();
                const existing = [...container.querySelectorAll('.toast')].find(t => t.dataset.sig === sig);
                if (cfg.dedupe && (existing || (Date.now() - (this._history.get(sig) || 0)) < cfg.dedupeWindow)) {
                    if (existing?._t) { clearTimeout(existing._t); existing._t = setTimeout(() => this.closeToast(existing.id), cfg.duration); }
                    return existing?.id || null;
                }
                while (cfg.maxVisible > 0 && container.children.length >= cfg.maxVisible) {
                    const oldest = container.firstElementChild;
                    if (oldest) { if (oldest._t) clearTimeout(oldest._t); oldest.remove(); }
                }
                this._history.set(sig, Date.now());
                const id = 'toast-' + Date.now();
                const el = document.createElement('div');
                el.className = `toast ${type}`;
                el.id = id;
                el.dataset.sig = sig;
                el.innerHTML = `<div class="toast-header"><span class="toast-title"><i class="${icon}"></i>${esc(title)}</span>${cfg.closable ? `<button class="toast-close" onclick="UXUtils.closeToast('${id}')"><i class="fas fa-times"></i></button>` : ''}</div><div class="toast-message">${esc(message)}</div>`;
                container.appendChild(el);
                requestAnimationFrame(() => requestAnimationFrame(() => el.classList.add('show')));
                if (cfg.duration > 0) el._t = setTimeout(() => this.closeToast(id), cfg.duration);
                return id;
            },
            closeToast(id) {
                const el = document.getElementById(id);
                if (!el) return;
                if (el._t) clearTimeout(el._t);
                el.classList.remove('show');
                setTimeout(() => el.remove(), 350);
            },
            closeAllToasts() { document.querySelectorAll('.toast').forEach(t => { t.classList.remove('show'); setTimeout(() => t.remove(), 350); }); },
            setLoading(el, on = true) { el.classList.toggle('loading', on); el.disabled = on; },
            validateForm(form) {
                let ok = true;
                form.querySelectorAll('[required]').forEach(i => {
                    if (!i.value.trim()) { i.classList.add('is-invalid'); ok = false; }
                    else { i.classList.remove('is-invalid'); }
                });
                return ok;
            }
        };

        /* ── Mobile table scroll ─────────────────────────────────────── */
        if (window.innerWidth < 768) {
            document.querySelectorAll('table').forEach(tbl => {
                if (!tbl.closest('.overflow-x-auto') && !tbl.closest('.table-responsive')) {
                    const w = document.createElement('div');
                    w.style.cssText = 'overflow-x:auto;-webkit-overflow-scrolling:touch;';
                    tbl.parentNode.insertBefore(w, tbl);
                    w.appendChild(tbl);
                }
            });
        }
    </script>

    @stack('scripts')
</body>
</html>
