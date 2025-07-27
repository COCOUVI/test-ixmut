@extends('layouts.master')

@section('title', "Historique de l'employé {$user->name} {$user->first_name}")
@section('page-title', "Historique de {$user->name} {$user->first_name}")

@section('content')

    <div class="max-w-6xl mx-auto px-4 py-8">

        <h1 class="font-bold text-[#164f63] mb-10">
            Détails sur l'employé : <span class="text-[#164f63] font-semibold capitalize">{{ $user->name ?? '' }}
                {{ $user->first_name ?? '' }}</span>
        </h1>

        @if (session('success'))
            <div id="successAlert"
                class="flex items-center justify-between p-4 mb-4 text-green-800 bg-green-100 border border-green-300 rounded-lg shadow-md">
                <div class="flex items-center space-x-2">
                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.707a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414L9 13.414l4.707-4.707z"
                            clip-rule="evenodd" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
                <button onclick="document.getElementById('successAlert').remove()"
                    class="text-green-600 hover:text-green-800 focus:outline-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        @endif



        {{-- TÂCHES --}}
        <section class="mb-16">
            <h2 class="font-bold text-[#164f63] border-b-4 border-[#164f63] pb-3 mb-8 capitalize">
                Tâches exécutées
            </h2>
            <form id="formTaches" action="{{ route('admin.taches.masquerMultiple') }}" method="POST">
                @csrf
                <div class="flex justify-end mb-4">
                    <button type="submit" id="openModalTaches" disabled
                        class="bg-red-600 text-white font-bold py-2 px-4 rounded shadow-md transition duration-150 disabled:opacity-50 disabled:cursor-not-allowed disabled:shadow-none">
                        <i class="fas fa-trash-alt mr-1"></i> Supprimer la sélection
                    </button>

                </div>

                @if ($taches->isEmpty())
                    <p class="italic text-gray-500">Aucune tâche enregistrée.</p>
                @else
                    <div class="overflow-x-auto rounded-lg border border-[#164f63] shadow-sm">
                        <table id="tachesTable" class="min-w-full bg-white text-[#164f63]">
                            <thead class="bg-[#164f63] text-white uppercase tracking-wide text-xs">
                                <tr>
                                    <th class="text-center px-4 sm:px-8 py-3 font-semibold">Description</th>
                                    <th class="text-center px-4 sm:px-8 py-3 font-semibold">Date</th>
                                    <th class="text-center px-4 sm:px-8 py-3 font-semibold">Heure</th>
                                    <th class="text-center px-4 sm:px-8 py-3 font-semibold">
                                        <input type="checkbox" id="selectAllTaches"
                                            class="form-checkbox h-5 w-5 text-[#164f63] rounded" />
                                    </th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($taches as $tache)
                                    <tr class="border-[#164f63] hover:bg-[#164f63]/10 transition text-center text-sm">
                                        <td class="px-4 sm:px-8 py-2">
                                            <button type="button"
                                                onclick="openDescriptionModal(`{{ addslashes($tache->description) }}`)"
                                                class="text-dark/95 hover:text-dark">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </td>

                                        <td class="px-4 sm:px-8 py-2">{{ $tache->created_at->format('d/m/Y') }}</td>
                                        <td class="px-4 sm:px-8 py-2">{{ $tache->created_at->format('H:i') }}</td>
                                        <td class="px-4 sm:px-8 py-2 text-center">
                                            {{-- <form action="{{ route('admin.taches.destroy', $tache->id) }}" method="POST"
                                                onsubmit="return confirm('Supprimer cette tâche ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button class="text-red-600 hover:text-red-800 font-semibold transition"
                                                    aria-label="Supprimer la tâche">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form> --}}
                                            <input type="checkbox" name="taches[]" value="{{ $tache->id }}"
                                                class="form-checkbox h-5 w-5 text-[#164f63] focus:ring-[#164f63] border-[#164f63] rounded" />


                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6">
                        {{ $taches->links() }}
                    </div>
                @endif
            </form>
        </section>

        {{-- POINTAGES --}}
        <section>
            <h2 class="font-bold text-[#164f63] border-b-4 border-[#164f63] pb-3 mb-8">
                Pointages
            </h2>
            <form id="formPointages" action="{{ route('admin.pointages.masquerMultiple') }}" method="POST">
                @csrf
                <div class="flex justify-end mb-4">
                    <button type="submit" id="openModalPointages" disabled
                        class="bg-red-600 text-white font-bold py-2 px-4 rounded shadow-md transition duration-150 disabled:opacity-50 disabled:cursor-not-allowed disabled:shadow-none">
                        <i class="fas fa-trash-alt mr-1"></i> Supprimer la sélection
                    </button>
                </div>

                @if ($pointages->isEmpty())
                    <p class="italic text-gray-500">Aucun pointage enregistré.</p>
                @else
                    <div class="overflow-x-auto rounded-lg border border-[#164f63] shadow-sm">
                        <table id="pointagesTable" class="min-w-full bg-white text-[#164f63]">
                            <thead class="bg-[#164f63] text-white uppercase tracking-wide text-xs">
                                <tr>
                                    <th class="px-4 sm:px-8 py-3 font-semibold text-center">Date</th>
                                    <th class="px-4 sm:px-8 py-3 font-semibold text-center">Arrivée</th>
                                    <th class="px-4 sm:px-8 py-3 font-semibold text-center">Départ</th>
                                    <th class="px-4 sm:px-8 py-3 font-semibold text-center">Motif retard</th>
                                    <th class="text-center px-4 sm:px-8 py-3 font-semibold">
                                        <input type="checkbox" id="selectAllPointages"
                                            class="form-checkbox h-5 w-5 text-[#164f63] rounded" />
                                    </th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pointages as $pointage)
                                    <tr class="hover:bg-[#164f63]/10 transition text-center text-sm">
                                        <td class="px-4 sm:px-8 py-2">
                                            {{ \Carbon\Carbon::parse($pointage->date)->format('d/m/Y') }}
                                        </td>
                                        <td class="px-4 sm:px-8 py-2 text-green-700 font-semibold">
                                            {{ $pointage->heure_arrivee }}
                                        </td>
                                        <td class="px-4 sm:px-8 py-2 text-red-700 font-semibold">
                                            {{ $pointage->heure_depart }}
                                        </td>
                                        <td class="px-4 sm:px-8 py-2">
                                            @if ($pointage->justificatif_retard)
                                                <button type="button"
                                                    onclick="openMotifModal(`{{ addslashes($pointage->justificatif_retard) }}`)"
                                                    class="text-dark/95 hover:text-dark">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            @else
                                                <span class="text-gray-500 italic">Aucun retard</span>
                                            @endif
                                        </td>
                                        <td class="px-4 sm:px-8 py-2 text-center">
                                            {{-- <form action="{{ route('admin.pointages.destroy', $pointage->id) }}" method="POST"
                                                onsubmit="return confirm('Supprimer ce pointage ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button class="text-red-600 hover:text-red-800 font-semibold transition"
                                                    aria-label="Supprimer le pointage">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form> --}}
                                            <input type="checkbox" name="pointages[]" value="{{ $pointage->id }}"
                                                class="form-checkbox h-5 w-5 text-[#164f63] focus:ring-[#164f63] border-[#164f63] rounded" />

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </form>
        </section>

    </div>

    <!-- MODALE DE DESCRIPTION AMÉLIORÉE -->
    <div id="descriptionModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 px-4">
        <div class="bg-white rounded-lg shadow-lg max-w-md w-full max-h-[80vh] overflow-y-auto p-6 relative">
            <h3 class="text-lg font-bold mb-4 text-[#164f63]">Description de la tâche</h3>
            <p id="descriptionContent" class="text-gray-700 whitespace-pre-wrap"></p>

            <!-- Bouton de fermeture -->
            <button onclick="closeDescriptionModal()" class="absolute top-3 right-3 text-gray-600 hover:text-gray-900">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>

    <!-- MODALE MOTIF RETARD -->
    <div id="motifModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 px-4">
        <div class="bg-white rounded-lg shadow-lg max-w-md w-full max-h-[80vh] overflow-y-auto p-6 relative">
            <h3 class="text-lg font-bold mb-4 text-[#164f63]">Motif du retard</h3>
            <p id="motifContent" class="text-gray-700 whitespace-pre-wrap"></p>

            <!-- Bouton de fermeture -->
            <button onclick="closeMotifModal()" class="absolute top-3 right-3 text-gray-600 hover:text-gray-900"
                aria-label="Fermer la modale">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>

    {{-- Modale de confirmation --}}
    <div id="modalTaches" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
            <h2 class="text-xl font-semibold mb-4">Confirmation</h2>
            <p>Voulez-vous vraiment supprimer les tâches sélectionnées ?</p>
            <div class="mt-6 flex justify-end space-x-3">
                <button id="cancelTaches"
                    class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">Annuler</button>
                <button id="confirmTaches"
                    class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Confirmer</button>
            </div>
        </div>
    </div>

    {{-- MODALE DE CONFIRMATION --}}
    <div id="modalPointages" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
            <h2 class="text-xl font-semibold mb-4">Confirmation</h2>
            <p>Voulez-vous vraiment supprimer les pointages sélectionnés ?</p>
            <div class="mt-6 flex justify-end space-x-3">
                <button id="cancelPointages"
                    class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">Annuler</button>
                <button id="confirmPointages"
                    class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Confirmer</button>
            </div>
        </div>
    </div>


@endsection

<script>
    function openDescriptionModal(text) {
        document.getElementById('descriptionContent').textContent = text;
        document.getElementById('descriptionModal').classList.remove('hidden');
        document.getElementById('descriptionModal').classList.add('flex');
    }

    function closeDescriptionModal() {
        document.getElementById('descriptionModal').classList.add('hidden');
        document.getElementById('descriptionModal').classList.remove('flex');
    }
</script>

<script>
    function openMotifModal(text) {
        document.getElementById('motifContent').textContent = text;
        document.getElementById('motifModal').classList.remove('hidden');
        document.getElementById('motifModal').classList.add('flex');
    }

    function closeMotifModal() {
        document.getElementById('motifModal').classList.add('hidden');
        document.getElementById('motifModal').classList.remove('flex');
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const selectAllTaches = document.getElementById('selectAllTaches');
        if (selectAllTaches) {
            selectAllTaches.addEventListener('change', function () {
                const checkboxes = document.querySelectorAll('#tachesTable tbody input[type="checkbox"]');
                checkboxes.forEach(cb => cb.checked = selectAllTaches.checked);
            });
        }

        const selectAllPointages = document.getElementById('selectAllPointages');
        if (selectAllPointages) {
            selectAllPointages.addEventListener('change', function () {
                const checkboxes = document.querySelectorAll('#pointagesTable tbody input[type="checkbox"]');
                checkboxes.forEach(cb => cb.checked = selectAllPointages.checked);
            });
        }
    });

</script>
{{--
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Ouverture modale pour pointages
        const openModalPointagesBtn = document.getElementById('openModalPointages');
        const modalPointages = document.getElementById('modalPointages');
        const cancelPointages = document.getElementById('cancelPointages');
        const confirmPointages = document.getElementById('confirmPointages');
        const formPointages = document.getElementById('formPointages');

        openModalPointagesBtn.addEventListener('click', function (e) {
            e.preventDefault(); // Empêche la soumission du formulaire
            modalPointages.classList.remove('hidden');
            modalPointages.classList.add('flex');
        });

        cancelPointages.addEventListener('click', function () {
            modalPointages.classList.add('hidden');
            modalPointages.classList.remove('flex');
        });

        confirmPointages.addEventListener('click', function () {
            formPointages.submit(); // Soumet le formulaire seulement après confirmation
        });
    });
</script>



<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Sélection des éléments
        const formTaches = document.getElementById('formTaches');
        const modalTaches = document.getElementById('modalTaches');
        const btnConfirmTaches = document.getElementById('confirmTaches');
        const btnCancelTaches = document.getElementById('cancelTaches');
        const btnSubmitTaches = formTaches.querySelector('button[type="submit"]');

        if (formTaches && modalTaches && btnConfirmTaches && btnCancelTaches) {
            // Bloquer la soumission normale
            formTaches.addEventListener('submit', function (e) {
                e.preventDefault();

                // Vérifier qu'au moins une checkbox est cochée (optionnel)
                const checkedBoxes = formTaches.querySelectorAll('input[type="checkbox"]:checked');
                if (checkedBoxes.length === 0) {
                    alert('Veuillez sélectionner au moins une tâche à supprimer.');
                    return;
                }

                // Afficher la modale
                modalTaches.classList.remove('hidden');
                modalTaches.classList.add('flex');
            });

            // Clic sur annuler
            btnCancelTaches.addEventListener('click', function () {
                modalTaches.classList.add('hidden');
                modalTaches.classList.remove('flex');
            });

            // Clic sur confirmer
            btnConfirmTaches.addEventListener('click', function () {
                modalTaches.classList.add('hidden');
                modalTaches.classList.remove('flex');

                // Soumettre le formulaire
                formTaches.submit();
            });
        }
    });

</script> --}}

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // POINTAGES
        const openModalPointagesBtn = document.getElementById('openModalPointages');
        const modalPointages = document.getElementById('modalPointages');
        const cancelPointages = document.getElementById('cancelPointages');
        const confirmPointages = document.getElementById('confirmPointages');
        const formPointages = document.getElementById('formPointages');

        openModalPointagesBtn.addEventListener('click', function (e) {
            e.preventDefault(); // Empêche la soumission du formulaire
            modalPointages.classList.remove('hidden');
            modalPointages.classList.add('flex');
        });

        cancelPointages.addEventListener('click', function () {
            modalPointages.classList.add('hidden');
            modalPointages.classList.remove('flex');
        });

        confirmPointages.addEventListener('click', function () {
            formPointages.submit(); // Soumet le formulaire seulement après confirmation
        });


        // TÂCHES
        const openModalTachesBtn = document.getElementById('openModalTaches');
        const modalTaches = document.getElementById('modalTaches');
        const cancelTaches = document.getElementById('cancelTaches');
        const confirmTaches = document.getElementById('confirmTaches');
        const formTaches = document.getElementById('formTaches');

        openModalTachesBtn.addEventListener('click', function (e) {
            e.preventDefault(); // Empêche la soumission du formulaire
            modalTaches.classList.remove('hidden');
            modalTaches.classList.add('flex');
        });

        cancelTaches.addEventListener('click', function () {
            modalTaches.classList.add('hidden');
            modalTaches.classList.remove('flex');
        });

        confirmTaches.addEventListener('click', function () {
            formTaches.submit(); // Soumet le formulaire seulement après confirmation
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Sélecteurs des formulaires et boutons
        const formTaches = document.getElementById('formTaches');
        const btnSupprTaches = document.getElementById('openModalTaches');
        const checkboxesTaches = formTaches.querySelectorAll('input[type="checkbox"][name="taches[]"]');

        const formPointages = document.getElementById('formPointages');
        const btnSupprPointages = document.getElementById('openModalPointages');
        const checkboxesPointages = formPointages.querySelectorAll('input[type="checkbox"][name="pointages[]"]');

        // Fonction qui active ou désactive un bouton selon si au moins 1 checkbox est cochée
        function toggleButton(button, checkboxes) {
            const checkedOne = Array.from(checkboxes).some(chk => chk.checked);
            button.disabled = !checkedOne;
        }

        // Pour chaque checkbox taches, on écoute le changement
        checkboxesTaches.forEach(chk => {
            chk.addEventListener('change', () => {
                toggleButton(btnSupprTaches, checkboxesTaches);
            });
        });

        // Pour chaque checkbox pointages, on écoute le changement
        checkboxesPointages.forEach(chk => {
            chk.addEventListener('change', () => {
                toggleButton(btnSupprPointages, checkboxesPointages);
            });
        });

        // Aussi, gérer les "select all" qui cochent/décochent tout
        const selectAllTaches = document.getElementById('selectAllTaches');
        if (selectAllTaches) {
            selectAllTaches.addEventListener('change', function () {
                checkboxesTaches.forEach(chk => chk.checked = this.checked);
                toggleButton(btnSupprTaches, checkboxesTaches);
            });
        }

        const selectAllPointages = document.getElementById('selectAllPointages');
        if (selectAllPointages) {
            selectAllPointages.addEventListener('change', function () {
                checkboxesPointages.forEach(chk => chk.checked = this.checked);
                toggleButton(btnSupprPointages, checkboxesPointages);
            });
        }

        // Initialisation : désactive les boutons si rien de coché au chargement
        toggleButton(btnSupprTaches, checkboxesTaches);
        toggleButton(btnSupprPointages, checkboxesPointages);
    });

</script>
