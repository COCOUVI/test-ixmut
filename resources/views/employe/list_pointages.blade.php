@extends('layouts.master')
@section('title', 'Historique des Pointages')
@section('page-title', 'Historique des Pointages')

@section('content')
    <div class="px-4 sm:px-6 lg:px-8 py-10">
        <div class="max-w-7xl mx-auto bg-primary rounded-2xl shadow-lg overflow-hidden">
            <div class="bg-dark px-6 py-4 rounded-t-2xl">
                <h2 class="sm:text-2xl font-bold text-white text-center">🕒 Mes pointages</h2>
            </div>

            <div class="p-4 sm:p-6">
                @if($pointages->isEmpty())
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 text-center">
                        <p class="text-dark font-medium">Aucun pointage effectué pour l’instant.</p>
                    </div>
                @else
                    <!-- Table sur écran moyen et plus -->
                    <div class="hidden md:block overflow-x-auto rounded-lg">
                        <table class="min-w-full text-sm border-separate border-spacing-y-2">
                            <thead class="bg-dark text-primary uppercase text-xs">
                                <tr>
                                    <th class="py-3 px-4 text-center">N°</th>
                                    <th class="py-3 px-4 text-center">Date</th>
                                    <th class="py-3 px-4 text-center">Arrivée</th>
                                    <th class="py-3 px-4 text-center">Départ</th>
                                    <th class="py-3 px-4 text-center">Retard</th>
                                    <th class="py-3 px-4 text-center">Motif</th>
                                    <th class="py-3 px-4 text-center">Statut</th>
                                    <th class="py-3 px-4 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white text-center text-gray-800">
                                @foreach($pointages as $pointage)
                                    <tr class="hover:bg-dark/5 transition">
                                        <td class="py-3 px-4 font-semibold">{{ $loop->iteration }}</td>
                                        <td class="py-3 px-4">{{ \Carbon\Carbon::parse($pointage->date_pointage)->format('d/m/Y') }}
                                        </td>
                                        <td class="py-3 px-4">{{ $pointage->heure_arrivee ?? '—' }}</td>
                                        <td class="py-3 px-4">{{ $pointage->heure_depart ?? '—' }}</td>
                                        <td class="py-3 px-4">
                                            @if($pointage->justificatif_retard)
                                                <span
                                                    class="bg-yellow-400 text-dark text-xs font-semibold px-2 py-1 rounded-full">Oui</span>
                                            @else
                                                <span
                                                    class="bg-green-500 text-white text-xs font-semibold px-2 py-1 rounded-full">Non</span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-4">{{ $pointage->justificatif_retard ?? '—' }}</td>
                                        <td class="py-3 px-4">
                                            @if($pointage->statut)
                                                <span
                                                    class="bg-dark text-white text-xs font-semibold px-2 py-1 rounded-full">Validé</span>
                                            @else
                                                <span class="bg-gray-400 text-white text-xs font-semibold px-2 py-1 rounded-full">En
                                                    attente</span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-4">
                                            <form action="{{ route('employe.pointages.destroy', $pointage) }}" method="POST" class="form-delete-pointage">
                                                @csrf
                                                @method('DELETE')
                                                <button class="text-red-600 hover:text-red-800 font-bold text-sm">🗑
                                                    Supprimer</button>
                                            </form>

                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Version mobile en cartes -->
                    <div class="md:hidden space-y-4">
                        @foreach($pointages as $pointage)
                            <div class="bg-white rounded-xl p-4 shadow">
                                <div class="flex justify-between text-sm font-semibold text-gray-600">
                                    <span>Date :</span>
                                    <span>{{ \Carbon\Carbon::parse($pointage->date_pointage)->format('d/m/Y') }}</span>
                                </div>
                                <div class="flex justify-between text-sm mt-2">
                                    <span>Arrivée :</span>
                                    <span>{{ $pointage->heure_arrivee ?? '—' }}</span>
                                </div>
                                <div class="flex justify-between text-sm mt-1">
                                    <span>Départ :</span>
                                    <span>{{ $pointage->heure_depart ?? '—' }}</span>
                                </div>
                                <div class="flex justify-between text-sm mt-1">
                                    <span>Retard :</span>
                                    <span>
                                        @if($pointage->justificatif_retard)
                                            <span class="text-yellow-600 font-bold">Oui</span>
                                        @else
                                            <span class="text-green-600 font-bold">Non</span>
                                        @endif
                                    </span>
                                </div>
                                <div class="text-sm mt-1">
                                    <span class="font-semibold text-gray-600">Motif :</span><br>
                                    <span>{{ $pointage->justificatif_retard ?? '—' }}</span>
                                </div>
                                <div class="text-sm mt-2">
                                    <span class="font-semibold text-gray-600">Statut :</span>
                                    @if($pointage->statut)
                                        <span class="ml-2 text-white bg-dark px-2 py-1 rounded-full text-xs">Validé</span>
                                    @else
                                        <span class="ml-2 text-white bg-gray-400 px-2 py-1 rounded-full text-xs">En attente</span>
                                    @endif
                                </div>
                                <form action="{{ route('employe.pointages.destroy', $pointage) }}" method="POST" class="form-delete-pointage">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-600 hover:text-red-800 font-bold text-sm mt-3">🗑 Supprimer</button>
                                </form>


                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $pointages->links() }}
                    </div>
                @endif
                {{-- <div class="text-right mt-4">
                    <form method="POST" action="{{ route('employe.pointages.destroyAll') }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Supprimer tout</button>
                    </form>
                </div> --}}

            </div>
        </div>
    </div>
    @push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.form-delete-pointage').forEach(form => {
            form.addEventListener('submit', function () {
                // Action automatique : tu peux ajouter des logs ou effets ici si tu veux
                console.log('Suppression en cours...');
            });
        });
    });
</script>
@endpush


@endsection
