<!-- Header -->
<header class="sticky top-0 z-50 bg-[#164f63] text-white shadow-md px-4 py-4 flex justify-between items-center">

    <!-- Bouton burger mobile -->
    <button @click="sidebarOpen = !sidebarOpen" class="md:hidden text-white hover:text-blue-200 transition-colors">
        <i class="fas fa-bars text-xl"></i>
    </button>

    <div class="absolute inset-0 flex justify-center items-center pointer-events-none md:hidden">
        <img src="{{ asset('images/Logo IMUXT (Blanc).png') }}" alt="Logo IMUXT" class="h-4 w-auto">
    </div>

    <!-- Espace vide ou autres éléments à droite -->
    <div class="flex items-center space-x-4 md:space-x-6 ml-auto md:pr-8">
        <!-- Notifications (uniquement pour l'admin) -->
        @php
            $user = auth()->user();
        @endphp

        @if ($user && $user->role === 'admin') {{-- Vérifie le rôle --}}
            @php
                $notifications = $user->unreadNotifications;
            @endphp

            <div class="relative" x-data="{ openNotif: false }" @click.outside="openNotif = false">
                <button @click="openNotif = !openNotif" class="relative">
                    <i class="fas fa-bell text-lg"></i>
                    @if ($notifications->count())
                        <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs px-1.5 py-0.5 rounded-full">
                            {{ $notifications->count() }}
                        </span>
                    @endif
                </button>
                <div x-show="openNotif" x-transition
                    class="absolute right-0 mt-2 w-72 bg-white text-black rounded-md shadow-lg z-50">
                    <div class="p-4">
                        <h6 class="font-semibold mb-3 text-gray-800">Notifications</h6>
                        <div class="space-y-3 max-h-60 overflow-y-auto">
                            @forelse ($notifications as $notification)
                                <a href="{{ route('notification.rediriger', $notification->id) }}"
                                    class="flex items-start space-x-3 hover:bg-gray-100 p-2 rounded-md">
                                    <div class="bg-blue-600 text-white p-2 rounded-full">
                                        <i class="fas fa-user-check text-sm"></i>
                                    </div>
                                    <div class="text-sm">
                                        <p class="font-medium">{{ $notification->data['titre'] ?? 'Notification' }}</p>
<p class="text-xs text-gray-500">{{ $notification->data['message'] ?? '' }}</p>

                                        <p class="text-xs text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                                    </div>
                                </a>
                            @empty
                                <p class="text-sm text-gray-500">Aucune notification</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Profil utilisateur -->
        <div class="relative" x-data="{ openUser: false }" @click.outside="openUser = false">
            <button @click="openUser = !openUser" class="flex items-center space-x-2">
                <img src="{{ $user->profile_photo ? asset('storage/' . $user->profile_photo) : asset('img/boy.png') }}"
                    class="w-8 h-8 md:w-10 md:h-10 rounded-full object-cover" alt="Profil">
                <span class="hidden lg:inline">{{ $user->name ?? 'Utilisateur' }}</span>
            </button>
            <div x-show="openUser" x-transition
                class="absolute right-0 mt-2 w-44 sm:w-48 bg-white text-black rounded shadow-lg z-50">
                <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2 hover:bg-gray-100">
                    <i class="fas fa-user mr-2 text-sm"></i>Mon profil
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center w-full text-left px-4 py-2 hover:bg-gray-100">
                        <i class="fas fa-sign-out-alt mr-2 text-sm"></i>Se déconnecter
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
