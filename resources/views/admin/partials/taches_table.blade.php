<h2 class="text-base md:text-xl font-semibold text-gray-800 mb-6 border-b-2 border-dark pb-2 text-capitalize">
    Tâches exécutées</h2>

@if ($taches->isEmpty())
    <p class="text-gray-500 italic">Aucune tâche enregistrée.</p>
@else
    <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-300 rounded-lg overflow-hidden text-sm md:text-base">
            <thead class="bg-indigo-100">
                <tr>
                    <th class="py-3 px-4 border-b text-left">Description</th>
                    <th class="py-3 px-4 border-b text-left">Date</th>
                    <th class="py-3 px-4 border-b text-left">Heure</th>
                    <th class="py-3 px-4 border-b text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($taches as $tache)
                    <tr class="hover:bg-indigo-50">
                        <td class="px-4 py-2 border-b">{{ $tache->description }}</td>
                        <td class="px-4 py-2 border-b">{{ $tache->created_at->format('d/m/Y') }}</td>
                        <td class="px-4 py-2 border-b">{{ $tache->created_at->format('H:i') }}</td>
                        <td class="px-4 py-2 border-b text-center">
                            <form action="{{ route('taches.destroy', $tache->id) }}" method="POST"
                                onsubmit="return confirm('Supprimer cette tâche ?');">
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

    <div class="mt-4">{{ $taches->links() }}</div>
@endif
