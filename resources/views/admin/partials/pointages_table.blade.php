<h2 class="text-base md:text-xl font-semibold text-gray-800 mb-6 border-b-2 border-indigo-600 pb-2">Pointages
</h2>

@if ($pointages->isEmpty())
    <p class="text-gray-500 italic">Aucun pointage enregistré.</p>
@else
    <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-300 rounded-lg overflow-hidden text-sm md:text-base">
            <thead class="bg-indigo-100">
                <tr>
                    <th class="py-3 px-4 border-b">Date</th>
                    <th class="py-3 px-4 border-b">Arrivée</th>
                    <th class="py-3 px-4 border-b">Départ</th>
                    <th class="py-3 px-4 border-b text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pointages as $pointage)
                    <tr class="hover:bg-indigo-50">
                        <td class="px-4 py-2 border-b">{{ \Carbon\Carbon::parse($pointage->date)->format('d/m/Y') }}
                        </td>
                        <td class="px-4 py-2 border-b text-green-600 font-semibold">{{ $pointage->heure_arrivee }}</td>
                        <td class="px-4 py-2 border-b text-red-600 font-semibold">{{ $pointage->heure_depart }}</td>
                        <td class="px-4 py-2 border-b text-center">
                            <form action="{{ route('pointages.destroy', $pointage->id) }}" method="POST"
                                onsubmit="return confirm('Supprimer ce pointage ?');">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600 hover:text-red-800 font-semibold text-sm">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
