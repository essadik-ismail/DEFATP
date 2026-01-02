<div class="mobile-nav lg:hidden" x-data="{ open: false }">
    <!-- Mobile menu button -->
    <button @click="open = !open" class="mobile-menu-button p-2 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100">
        <i class="fas fa-bars text-xl"></i>
    </button>

    <!-- Mobile menu overlay -->
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 lg:hidden">
        
        <!-- Backdrop -->
        <div @click="open = false" class="fixed inset-0 bg-black bg-opacity-50"></div>
        
        <!-- Menu panel -->
        <div class="fixed inset-y-0 right-0 max-w-xs w-full bg-white shadow-xl">
            <div class="flex items-center justify-between p-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Menu</h2>
                <button @click="open = false" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <nav class="px-4 py-6 space-y-2">
                <a href="{{ route('dashboard') }}" 
                   class="mobile-nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home me-3"></i>
                    Tableau de Bord
                </a>

                <a href="{{ route('reports.index') }}" 
                   class="mobile-nav-item {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                    <i class="fas fa-chart-bar me-3"></i>
                    Rapports
                </a>
                
                <a href="{{ route('articles.index') }}" 
                   class="mobile-nav-item {{ request()->routeIs('articles.*') ? 'active' : '' }}">
                    <i class="fas fa-file-alt me-3"></i>
                    Articles
                </a>
                
                <a href="{{ route('exploitants.index') }}" 
                   class="mobile-nav-item {{ request()->routeIs('exploitants.*') ? 'active' : '' }}">
                    <i class="fas fa-file-alt me-3"></i>
                    Exploitants
                </a>
                
                <a href="{{ route('coperatives.index') }}" 
                   class="mobile-nav-item {{ request()->routeIs('coperatives.*') ? 'active' : '' }}">
                    <i class="fas fa-users-cog me-3"></i>
                    Coopératives
                </a>
                
                <a href="{{ route('entity-data.index') }}" 
                   class="mobile-nav-item {{ request()->routeIs('entity-data.*') || request()->routeIs('essences.*') || request()->routeIs('forets.*') || request()->routeIs('situations.*') || request()->routeIs('natures.*') || request()->routeIs('vocations.*') || request()->routeIs('coperatives.*') || request()->routeIs('products.*') || request()->routeIs('prestations.*') ? 'active' : '' }}">
                    <i class="fas fa-database me-3"></i>
                    Données des Entités
                </a>
                
                <a href="{{ route('financial-data.index') }}" 
                   class="mobile-nav-item {{ request()->routeIs('financial-data.*') ? 'active' : '' }}">
                    <i class="fas fa-money-bill-wave me-3"></i>
                    Recette
                </a>
                
                <a href="{{ route('partenariats.index') }}" 
                   class="mobile-nav-item {{ request()->routeIs('partenariats.*') ? 'active' : '' }}">
                    <i class="fas fa-handshake me-3"></i>
                    Partenariats
                </a>
                
                <a href="{{ route('settings.index') }}" 
                   class="mobile-nav-item {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                    <i class="fas fa-cog me-3"></i>
                    Paramètres
                </a>
                
                
                <a href="{{ route('excel.index') }}" 
                   class="mobile-nav-item {{ request()->routeIs('excel.*') ? 'active' : '' }}">
                    <i class="fas fa-file-excel me-3"></i>
                    Import/Export
                </a>
                
                <a href="{{ route('auth.users.index') }}" 
                   class="mobile-nav-item {{ request()->routeIs('auth.users.*') ? 'active' : '' }}">
                    <i class="fas fa-users me-3"></i>
                    Utilisateurs
                </a>
                
                
                <div class="border-t border-gray-200 pt-4 mt-4">
                    <a href="{{ route('auth.profile') }}" 
                       class="mobile-nav-item {{ request()->routeIs('auth.profile') ? 'active' : '' }}">
                        <i class="fas fa-user me-3"></i>
                        Mon Profil
                    </a>
                    
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="mobile-nav-item w-full text-left">
                            <i class="fas fa-sign-out-alt me-3"></i>
                            Déconnexion
                        </button>
                    </form>
                </div>
            </nav>
        </div>
    </div>
</div>

<style>
.mobile-nav-item {
    @apply block w-full px-3 py-2 text-base font-medium text-gray-700 rounded-md hover:text-gray-900 hover:bg-gray-50 transition-colors duration-150;
}

.mobile-nav-item.active {
    @apply bg-blue-100 text-blue-900;
}
</style>
