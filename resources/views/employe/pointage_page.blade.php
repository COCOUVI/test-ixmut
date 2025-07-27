@extends('layouts.master')
@section('title', 'Pointages')
@section('page-title', 'Pointages')

@section('content')
    @if (session('success') || session('error'))
        <div class="fixed bottom-4 right-4 z-[1055]">
            <div id="liveToast" class="toast flex items-center text-white rounded shadow-lg p-4
                        {{ session('success') ? 'bg-green-600' : 'bg-red-600' }}" role="alert" aria-live="assertive"
                aria-atomic="true">
                <div class="flex-grow font-semibold">
                    {{ session('success') ?? session('error') }}
                </div>
                <button type="button" class="ml-4 text-white hover:text-gray-200 focus:outline-none" id="toastCloseBtn">
                    ✕
                </button>
            </div>
        </div>
    @endif

    <div class="container mx-auto py-12 px-4">
        <div class="max-w-3xl mx-auto bg-dark/90 rounded-xl shadow-lg overflow-hidden border border-primary/30">
            <div class="p-8">
                <h4 class="text-base font-extrabold text-primary mb-6 text-center">📍 Pointage du personnel</h4>
                <p class="text-primary/80 mb-8">Veuillez choisir l’action à effectuer ci-dessous.</p>

                <form id="pointageForm" method="POST" action="{{ route('pointage.store') }}" novalidate>
                    @csrf
                    <input type="hidden" name="type" id="inputType" />
                    {{-- <input type="hidden" name="retard" id="inputRetard" value="0" /> --}}

                    <div class="flex flex-wrap gap-6 mb-8">
                        <button type="button" id="btnArrivee"
                            class="flex items-center gap-2 px-8 py-3 rounded-lg shadow-lg bg-primary text-dark font-semibold hover:bg-white transition disabled:opacity-50 disabled:cursor-not-allowed"
                            aria-label="Pointer l’arrivée">
                            <i class="mdi mdi-login"></i> Pointer l’arrivée
                        </button>

                        <button type="button" id="btnDepart"
                            class="flex items-center gap-2 px-8 py-3 rounded-lg shadow-lg bg-green-500 text-white hover:bg-green-600 transition disabled:opacity-50 disabled:cursor-not-allowed"
                            aria-label="Pointer le départ">
                            <i class="mdi mdi-logout"></i> Pointer le départ
                        </button>
                    </div>

                    <div id="motifRetardContainer"
                        class="hidden bg-yellow-50 border-l-4 border-yellow-400 p-5 rounded-lg mb-6 shadow-inner">
                        <label for="motif_retard" class="block font-semibold mb-3 text-yellow-800">
                            ⏰ Vous êtes en retard. Merci d’indiquer le motif :
                        </label>
                        <textarea id="motif_retard" name="motif_retard" rows="3"
                            class="w-full rounded-md border border-yellow-300 p-3 text-yellow-900 placeholder-yellow-500 focus:outline-none focus:ring-2 focus:ring-yellow-400 transition"
                            placeholder="Ex : embouteillage, souci familial..." required></textarea>

                        <button type="submit" id="submitBtnWithMotif"
                            class="mt-5 w-full bg-primary text-dark font-semibold py-3 rounded-lg shadow-lg hover:bg-white transition disabled:opacity-50"
                            disabled>
                            Envoyer
                        </button>
                    </div>

                    <div id="submitBtnContainer">
                        <button type="submit" id="submitBtn"
                            class="w-full bg-primary text-dark font-semibold py-3 rounded-lg shadow-lg hover:bg-white transition disabled:opacity-50"
                            disabled>
                            Envoyer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const hasArrivee = {{ json_encode($hasArrivee) }};
            const hasDepart = {{ json_encode($hasDepart) }};

            const btnArrivee = document.getElementById('btnArrivee');
            const btnDepart = document.getElementById('btnDepart');
            const inputType = document.getElementById('inputType');
            const inputRetard = document.getElementById('inputRetard');
            const motifContainer = document.getElementById('motifRetardContainer');
            const motifTextarea = document.getElementById('motif_retard');
            const submitBtn = document.getElementById('submitBtn');
            const submitBtnWithMotif = document.getElementById('submitBtnWithMotif');
            const submitBtnContainer = document.getElementById('submitBtnContainer');
            const form = document.getElementById('pointageForm');

            let currentType = '';
            let retard = 0;

            // Activation des boutons
            btnArrivee.disabled = hasArrivee;
            btnDepart.disabled = !hasArrivee || hasDepart;

            function updateSubmitBtnState() {
                submitBtn.disabled = (currentType === '');
            }

            function updateMotifSubmitBtnState() {
                submitBtnWithMotif.disabled = motifTextarea.value.trim() === '';
            }

            btnArrivee.addEventListener('click', () => {
                currentType = 'arrivee';
                inputType.value = currentType;

                const now = new Date();
                const heure = now.getHours();
                const minute = now.getMinutes();

                // Comparer avec 08:30
                if (heure > 10 || (heure === 10 && minute > 00)) {
                    // En retard
                    motifContainer.classList.remove('hidden');
                    submitBtnContainer.classList.add('hidden');
                    motifTextarea.value = '';
                    submitBtnWithMotif.disabled = true;
                    motifTextarea.focus();
                } else {
                    // À l’heure → soumission directe
                    motifContainer.classList.add('hidden');
                    submitBtnContainer.classList.remove('hidden');
                    submitBtn.disabled = false;
                    submitBtn.focus();
                }
            });


            //         btnDepart.addEventListener('click', () => {
            //     currentType = 'depart';
            //     inputType.value = currentType;
            //     retard = 0;
            //     inputRetard.value = 0;
            //     motifContainer.classList.add('hidden');
            //     submitBtnContainer.classList.remove('hidden');
            //     updateSubmitBtnState();
            //     submitBtn.focus();
            // });

            btnDepart.addEventListener('click', () => {
                currentType = 'depart';
                inputType.value = currentType;
                motifContainer.classList.add('hidden');
                submitBtnContainer.classList.remove('hidden');
                submitBtn.disabled = false;
                submitBtn.focus();
            });



            motifTextarea.addEventListener('input', () => {
                submitBtnWithMotif.disabled = motifTextarea.value.trim() === '';
            });


            form.addEventListener('submit', (e) => {
                if (!currentType) {
                    e.preventDefault();
                    return;
                }
            });


            // Toast close button
            const toastCloseBtn = document.getElementById('toastCloseBtn');
            if (toastCloseBtn) {
                toastCloseBtn.addEventListener('click', () => {
                    toastCloseBtn.closest('#liveToast').remove();
                });
            }

            updateSubmitBtnState();
        });
    </script>
@endsection
