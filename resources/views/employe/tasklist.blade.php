@extends("layouts.master")
@section('title', 'Historique des Tâches')
@section('page-title', 'Historique des Tâches')

@section("content")
<div class="px-4 sm:px-6 lg:px-8 py-10">
    <div class="max-w-7xl mx-auto bg-primary rounded-2xl shadow-lg overflow-hidden">
        <!-- Header -->
        <div class="bg-dark px-6 py-4 rounded-t-2xl">
            <h2 class="font-bold text-white text-center">📋 Historique de vos tâches</h2>
        </div>

        <!-- Contenu -->
        <div class="p-4 sm:p-6 text-center">
            @if($taches->isEmpty())
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 text-center">
                    <div class="mx-auto w-16 h-16 flex items-center justify-center bg-blue-100 rounded-full mb-4">
                        <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class=" font-semibold text-dark mb-2">Aucune tâche enregistrée</h3>
                    <p class="text-gray-600">Votre historique de tâches apparaîtra ici</p>
                </div>
            @else
                <div class="overflow-x-auto rounded-lg">
                    <table class="min-w-full table-auto border-separate border-spacing-y-2">
                        <thead class="bg-dark text-primary uppercase text-xs sm:text-sm">
                            <tr>
                                <th class="py-3 px-4 rounded-tl-lg ">N°</th>
                                <th class="py-3 px-4 ">Description</th>
                                <th class="py-3 px-4">Date</th>
                                <th class="py-3 px-4 rounded-tr-lg">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white text-gray-800">
                            @foreach($taches as $index => $tache)
                            <tr class="hover:bg-dark/5 transition">
                                <td class="py-3 px-4 font-semibold">{{ $index + 1 }}</td>

                                <!-- Bouton voir description -->
                                <td class="py-3 px-4">
                                    <button
                                        class="text-dark text-center items-center space-x-1"
                                        onclick="openModal(`{{ addslashes($tache->description) }}`)"
                                        title="Voir description">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <span class="hidden sm:inline">Voir</span>
                                    </button>
                                </td>

                                <td class="py-3 px-4 text-sm">
                                    {{ $tache->created_at->format('d/m/Y') }}
                                    <div class="text-xs text-gray-400">{{ $tache->created_at->format('H:i') }}</div>
                                </td>
                                <td class="py-3 px-4">
                                    <form action="{{ route('taches.delete', $tache->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 transition" title="Supprimer">
                                            <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $taches->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal -->
<div id="descriptionModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50 px-4">
    <div class="bg-white rounded-lg max-w-lg w-full p-6 shadow-lg relative max-h-[80vh] overflow-y-auto">
        <button onclick="closeModal()" class="absolute top-3 right-3 text-gray-600 hover:text-gray-800 focus:outline-none" title="Fermer">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
        <h3 class="font-semibold mb-4">Description de la tâche</h3>
        <p id="modalDescription" class="whitespace-pre-line text-gray-800"></p>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function openModal(description) {
        const modal = document.getElementById('descriptionModal');
        const modalDesc = document.getElementById('modalDescription');
        modalDesc.textContent = description;
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        const modal = document.getElementById('descriptionModal');
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    window.addEventListener('click', function(event) {
        const modal = document.getElementById('descriptionModal');
        if (!modal.classList.contains('hidden') && !event.target.closest('.rounded-lg')) {
            closeModal();
        }
    });

    window.addEventListener('keydown', function(event) {
        if(event.key === "Escape") {
            closeModal();
        }
    });
</script>
@endpush
