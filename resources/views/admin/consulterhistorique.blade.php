@extends('layouts.master')

@section('title', 'Historique des comptes')

@section('page-title', 'Comptes Créés')

@section('content')

    <!-- TABLEAU DESKTOP -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 space-y-2 sm:space-y-0 mt-4">
        <input type="text" id="search" placeholder="Rechercher un employé..."
            class="border-1 border-dark p-2 pl-3 w-full sm:w-72 rounded-xl shadow-sm" autocomplete="off">

        <button type="button" onclick="openDeleteAllModal()"
            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-semibold shadow w-full sm:w-auto">
            Supprimer tous les comptes
        </button>
    </div>

    <div class="hidden md:block overflow-x-auto rounded-lg shadow-lg border border-[#164f63] mb-8">
        <table class="min-w-full border-collapse text-[#164f63] font-sans">
            <thead class="bg-[#164f63] text-white uppercase tracking-wide">
                <tr>
                    <th class="py-4 px-6 text-left">Nom complet</th>
                    <th class="py-4 px-6 text-left">Email</th>
                    <th class="py-4 px-6 text-left">Date création</th>
                    <th class="py-4 px-6 text-left">Statut</th>
                    <th class="py-4 px-6 text-center">Actions</th>
                </tr>
            </thead>
            <tbody id="table-body" class="bg-[#f9fafb]">
                @if($comptesCrees->isEmpty())
                    <tr>
                        <td colspan="5" class="py-4 text-center text-gray-500 italic">
                            Aucun comptes créé pour l'instant.
                        </td>
                    </tr>
                @else

                    @foreach ($comptesCrees as $user)
                        <!-- LIGNE PRINCIPALE -->
                        <tr class="border-b border-[#164f63]/20 hover:bg-[#e0f2ff] transition cursor-pointer"
                            onclick="toggleDetails({{ $user->id }})">
                            <td class="py-3 px-6 font-medium">{{ $user->name }} {{ $user->first_name }}</td>
                            <td class="py-3 px-6 break-words max-w-xs">{{ $user->email }}</td>
                            <td class="py-3 px-6 whitespace-nowrap">{{ $user->created_at->format('d/m/Y H:i') }}</td>
                            <td class="py-3 px-6">
                                @if ($user->active)
                                    <span
                                        class="inline-block px-3 py-1 rounded-full bg-green-100 text-green-700 font-semibold tracking-wide">Actif</span>
                                @else
                                    <span
                                        class="inline-block px-3 py-1 rounded-full bg-red-100 text-red-700 font-semibold tracking-wide">Désactivé</span>
                                @endif
                            </td>
                            <td class="py-3 px-6 text-center space-x-2 sm:pt-4 sm:justify-center sm:flex sm:items-center">
                                <a href="{{ route('employe.historique', $user->id) }}"
                                    class="inline-block text-dark px-4 py-1 font-semibold">
                                    <i class="fas fa-fw fa-eye w-5"></i>
                                </a>
                                <form action="{{ route('admin.user.toggle', $user->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="inline-block text-[#164f63] font-semibold px-4 py-1">
                                        @if ($user->active)
                                            <i class="fas fa-fw fa-toggle-on w-5 text-black"></i>
                                        @else
                                            <i class="fas fa-fw fa-toggle-off w-5 text-gray-400"></i>
                                        @endif
                                    </button>
                                </form>
                                <form action="{{ route('admin.user.destroy', $user->id) }}" method="POST" class="inline"
                                    onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce compte ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" onclick="openDeleteModal({{ $user->id }})"
                                        class="inline-block text-red-600 px-4 py-1 font-semibold">
                                        <i class="fas fa-fw fa-trash w-5 text-white-600"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        <!-- LIGNE DÉTAILS (CACHÉE AU DÉBUT) -->
                        <tr id="details-{{ $user->id }}" class="hidden bg-blue-50">
                            <td colspan="5" class="py-3 px-6">
                                <p class="font-semibold mb-1 mt-4">Pointages journaliers:</p>
                                <ul class="list-disc pl-5 text-sm text-gray-700 mb-4">
                                    @forelse ($user->pointages as $pointage)
                                        <li>
                                            {{ $pointage->date_pointage }} :
                                            {{ substr($pointage->heure_arrivee, 0, 5) }}
                                            @if ($pointage->heure_depart)
                                                - {{ substr($pointage->heure_depart, 0, 5) }}
                                            @else
                                                - <span class="italic text-gray-500">Pas encore parti</span>
                                            @endif
                                        </li>
                                    @empty
                                        <li>Aucun pointage trouvé.</li>
                                    @endforelse
                                </ul>

                                <p class="font-semibold mb-1 mt-4">Tâches journalière:</p>
                                <ul class="list-disc pl-5 text-sm text-gray-700">
                                    @forelse($user->taches as $task)
                                        <li>
                                            <span>
                                                {{ $task->description }}
                                                - <small>A :
                                                    {{ \Carbon\Carbon::parse($task->created_at)->format('H:i:s') }}</small>
                                            </span>
                                        </li>
                                    @empty
                                        <li>Aucune tâche enregistrée.</li>
                                    @endforelse
                                </ul>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
        <div class="mt-4">
            {{ $comptesCrees->links() }}
        </div>
    </div>

    <!-- AFFICHAGE MOBILE -->
    <div id="mobile-cards" class="md:hidden space-y-6">
        @if($comptesCrees->isEmpty())
            <tr>
                <td colspan="5" class="py-4 text-center text-gray-500 italic bg-red-500">
                    Aucun comptes créé pour l'instant.
                </td>
            </tr>
        @else
            @foreach ($comptesCrees as $user)
                <div class="bg-[#164f63] bg-opacity-10 rounded-xl p-5 shadow-lg border border-[#164f63]/30 cursor-pointer"
                    onclick="toggleMobileDetails({{ $user->id }})">
                    <p class="text-[#164f63] font-semibold mb-1"><span class="font-bold">Nom :</span> {{ $user->name }}
                        {{ $user->first_name }}
                    </p>
                    <p class="text-[#164f63] mb-1"><span class="font-bold">Email :</span> {{ $user->email }}</p>
                    <p class="text-[#164f63] mb-1"><span class="font-bold">Date création :</span>
                        {{ $user->created_at->format('d/m/Y H:i') }}</p>
                    <p class="mb-3"><span class="font-bold text-[#164f63]">Statut :</span>
                        @if ($user->active)
                            <span
                                class="inline-block px-3 py-1 rounded-full bg-green-100 text-green-700 font-semibold tracking-wide">Actif</span>
                        @else
                            <span
                                class="inline-block px-3 py-1 rounded-full bg-red-100 text-red-700 font-semibold tracking-wide">Désactivé</span>
                        @endif
                    </p>

                    <!-- Détails masqués au départ -->
                    <div id="mobile-details-{{ $user->id }}" class="hidden mt-2 text-sm text-gray-700">
                        <p class="font-semibold mb-1 mt-4">Pointages journaliers :</p>
                        <ul class="list-disc pl-5 text-sm text-gray-700 mb-4">
                            @forelse ($user->pointages as $pointage)
                                <li>
                                    {{ $pointage->date_pointage }} :
                                    {{ substr($pointage->heure_arrivee, 0, 5) }}
                                    @if ($pointage->heure_depart)
                                        - {{ substr($pointage->heure_depart, 0, 5) }}
                                    @else
                                        - <span class="italic text-gray-500">Pas encore parti</span>
                                    @endif
                                </li>
                            @empty
                                <li>Aucun pointage trouvé.</li>
                            @endforelse
                        </ul>
                        <p class="font-semibold mb-1 mt-4">Tâches journalières :</p>
                        <ul class="list-disc pl-5 text-sm text-gray-700">
                            @forelse($user->taches as $task)
                                <li>
                                    <span>
                                        {{ $task->description }}
                                        - <small>A :
                                            {{ \Carbon\Carbon::parse($task->created_at)->format('H:i:s') }}</small>
                                    </span>
                                </li>
                            @empty
                                <li>Aucune tâche enregistrée.</li>
                            @endforelse
                        </ul>
                    </div>

                    <div class="flex flex-wrap gap-3 justify-center mt-4">
                        <a href="{{ route('employe.historique', $user->id) }}"
                            class="bg-[#164f63] hover:bg-[#0f3a4c] text-white px-4 py-2 rounded-lg font-semibold text-sm shadow-md transition w-full sm:w-auto text-center">Voir
                            détails</a>
                        <form action="{{ route('admin.user.toggle', $user->id) }}" method="POST" class="w-full sm:w-auto">
                            @csrf
                            <button type="submit"
                                class="w-full bg-yellow-400 hover:bg-yellow-500 text-[#164f63] font-semibold px-4 py-2 rounded-lg text-sm shadow-md transition">
                                @if ($user->active)
                                    Désactiver
                                @else
                                    Activer
                                @endif
                            </button>
                        </form>
                        <form action="{{ route('admin.user.destroy', $user->id) }}" method="POST" class="w-full sm:w-auto"
                            onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce compte ?');">
                            @csrf
                            @method('DELETE')
                            <button type="button" onclick="openDeleteModal({{ $user->id }})"
                                class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-semibold text-sm shadow-md transition">
                                Supprimer
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        @endif

        <div class="mt-4">
            {{ $comptesCrees->links() }}
        </div>
    </div>

    <!-- MODALE DE CONFIRMATION -->
    <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center hidden z-50">
        <div class="bg-white p-6 rounded shadow-lg text-center w-[90%] max-w-md">
            <p class="mb-4 text-gray-800">Êtes-vous sûr de vouloir supprimer ce compte ?</p>
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="flex justify-center gap-4">
                    <button type="button" onclick="closeDeleteModal()"
                        class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">
                        Annuler
                    </button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                        Confirmer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODALE SUPPRESSION TOTALE -->
    <div id="deleteAllModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center hidden z-50">
        <div class="bg-white p-6 rounded shadow-lg text-center w-[90%] max-w-md">
            <p class="mb-4 text-gray-800">Es-tu sûr de vouloir supprimer <strong>tous les comptes</strong> ?</p>
            <form id="deleteAllForm" method="POST" action="{{ route('admin.users.destroyAll') }}">
                @csrf
                @method('DELETE')
                <div class="flex justify-center gap-4">
                    <button type="button" onclick="closeDeleteAllModal()"
                        class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">
                        Annuler
                    </button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                        Confirmer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openDeleteModal(userId) {
            const form = document.getElementById('deleteForm');
            form.action = "{{ url('admin/user') }}/" + userId;
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }
    </script>

    <script>
        function openDeleteAllModal() {
            document.getElementById('deleteAllModal').classList.remove('hidden');
        }

        function closeDeleteAllModal() {
            document.getElementById('deleteAllModal').classList.add('hidden');
        }
    </script>

    {{-- <script>
        document.getElementById('search').addEventListener('input', function () {
            const query = this.value;

            fetch(`{{ route('admin.rechercherEmployes') }}?q=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('table-body').innerHTML = data.htmlDesktop;
                    document.getElementById('mobile-cards').innerHTML = data.htmlMobile;
                });
        });
    </script> --}}

    {{-- <script>
    document.getElementById('search').addEventListener('input', function () {
        const query = this.value.trim(); // Enlève les espaces inutiles

        fetch(`{{ route('admin.rechercherEmployes') }}?q=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('table-body').innerHTML = data.htmlDesktop;
                document.getElementById('mobile-cards').innerHTML = data.htmlMobile;
            })
            .catch(error => {
                console.error('Erreur AJAX :', error);
            });
    });
</script> --}}

<script>
    let debounce;

    document.getElementById('search').addEventListener('input', function () {
        const query = this.value.trim();

        clearTimeout(debounce);
        debounce = setTimeout(() => {
            fetch(`{{ route('admin.rechercherEmployes') }}?q=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('table-body').innerHTML = data.htmlDesktop;
                    document.getElementById('mobile-cards').innerHTML = data.htmlMobile;
                })
                .catch(error => console.error('Erreur AJAX :', error));
        }, 300); // délai court pour éviter les appels trop rapides
    });
</script>



    <script>
        function toggleDetails(userId) {
            const row = document.getElementById(`details-${userId}`);
            row.classList.toggle('hidden');
        }

        function toggleMobileDetails(userId) {
            const details = document.getElementById(`mobile-details-${userId}`);
            details.classList.toggle('hidden');
        }
    </script>

@endsection
