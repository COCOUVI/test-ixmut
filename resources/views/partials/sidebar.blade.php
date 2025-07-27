<!-- Sidebar -->
<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-40 w-64 bg-dark shadow-md overflow-y-auto transform transition-transform duration-300 ease-in-out
           md:translate-x-0 md:static md:shadow-none ">
    <!-- Contenu Sidebar -->
    <div class="p-4 text-white">
        <div>
            @if (auth()->user()->role === 'admin')
                <a href="{{ route('admin.dashboard') }}" class="flex items-center justify-center">
                    <div class="w-20 h-10">
                        <img src="{{ asset('images/Logo IMUXT (Blanc).png') }}" alt="Logo"
                            class="w-full h-full object-contain">
                    </div>
                </a>
            @else
                <a href="{{ route('employe.dashboard') }}" class="flex items-center justify-center">
                    <div class="w-20 h-10">
                        <img src="{{ asset('images/Logo IMUXT (Blanc).png') }}" alt="Logo"
                            class="w-full h-full object-contain">
                    </div>
                </a>
            @endif

            <!-- Dashboard -->
            @php
                $user = auth()->user();
                $isAdmin = $user && $user->role === 'admin';
                $isEmploye = $user && $user->role === 'employe';
            @endphp

            <div class="p-3 space-y-1 flex-1 overflow-y-auto pt-7">

            </div>


        </div>

        <!-- Section ADMIN -->
        @if (auth()->user()->role === 'admin')
            <div class="pl-6 py-2 text-sm uppercase tracking-wider text-gray-400 sidebar-label">
                ADMINISTRATEUR
            </div>

            <div class="p-3 space-y-1 font-medium">
                <a href="{{ $isAdmin ? route('admin.dashboard') : ($isEmploye ? route('employe.dashboard') : '#') }}"
                    class="flex items-center p-2 rounded-lg hover:bg-gray-700 {{ request()->routeIs('admin.dashboard') || request()->routeIs('employe.dashboard') ? 'bg-gray-700' : '' }}">
                    <i class="fas fa-fw fa-home w-5 mr-3 text-center"></i>
                    <span class="sidebar-label">Acceuil</span>
                </a>
                <a href="{{ route('admin.register') }}" class="flex items-center p-2 rounded-lg hover:bg-gray-700">
                    <i class="fas fa-fw fa-user w-5 mr-3 text-center"></i>
                    <span class="sidebar-label">Créer Comptes</span>
                </a>
                <a href="{{ route('consulterhistorique') }}" class="flex items-center p-2 rounded-lg hover:bg-gray-700">
                    <i class="fas fa-fw fa-history w-5 mr-3 text-center"></i>
                    <span class="sidebar-label">Liste des Employés</span>
                </a>
            </div>
        @endif

        <!-- Section EMPLOYE -->
        @if (auth()->user()->role === 'employe')
            <div class="pl-6 py-2 text-sm uppercase tracking-wider text-gray-400 sidebar-label">
                EMPLOYE
            </div>

            <div class="p-3 space-y-1 font-medium">
                <a href="{{ $isAdmin ? route('employe.dashboard') : ($isEmploye ? route('employe.dashboard') : '#') }}"
                    class="flex items-center p-2 rounded-lg hover:bg-gray-700 {{ request()->routeIs('admin.dashboard') || request()->routeIs('employe.dashboard') ? 'bg-gray-700' : '' }}">
                    <i class="fas fa-fw fa-home w-5 mr-3 text-center"></i>
                    <span class="sidebar-label">Acceuil</span>
                </a>
                <a href="{{ route('pointages.index') }}" class="flex items-center p-2 rounded-lg hover:bg-gray-700">
                    <i class="fas fa-fw fa-user w-5 mr-3 text-center"></i>
                    <span class="sidebar-label">Pointages</span>
                </a>

                <a href="{{ route('taches.index') }}"
                    class="flex items-center p-2 rounded-lg hover:bg-gray-700 {{ request()->routeIs('taches.index') ? 'bg-gray-700' : '' }}">
                    <i class="fas fa-fw fa-tasks w-5 mr-3 text-center"></i>
                    <span class="sidebar-label">Tâches</span>
                </a>

                <div x-data="{ open: false }" class="rounded-lg hover:bg-gray-700">
                    <button @click="open = !open" class="flex items-center justify-between w-full p-2">
                        <div class="flex items-center">
                            <i class="fas fa-fw fa-history w-5 mr-3 text-center"></i>
                            <span class="sidebar-label">Historiques</span>
                        </div>
                        <i class="fas fa-chevron-down text-xs transform transition-transform"
                            :class="open ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="open" class="pl-8 py-1 space-y-1">
                        <div class=" text-gray-400 px-2 py-1 sidebar-label">Pointages & Tâches</div>
                        <a href="{{route('pointages.lists')}}"
                            class="block p-2rounded hover:bg-gray-600">Pointages</a>
                        <a href="{{ route('tasks.lists') }}"
                            class="block p-2 rounded hover:bg-gray-600 {{ request()->routeIs('tasks.lists') ? 'bg-gray-600' : '' }}">Tâches</a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</aside>
