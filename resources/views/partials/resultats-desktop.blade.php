@if($employes->isEmpty())
    <tr>
        <td colspan="5" class="py-4 text-center text-gray-500 italic">
            Aucun employé trouvé pour ce critère.
        </td>
    </tr>
@else
    @foreach ($employes as $user)
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
                <a href="{{ route('employe.historique', $user->id) }}" class="inline-block text-dark px-4 py-1 font-semibold">
                    <i class="fas fa-fw fa-eye w-5"></i>
                </a>
                <form action="{{ route('admin.user.toggle', $user->id) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="inline-block  text-[#164f63] font-semibold px-4 py-1">
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
                <p class="font-semibold mb-1 mt-4">>Pointages journaliers :</p>
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
                    @empty
                            <li>Aucune tâche enregistrée.</li>
                        @endforelse
                </ul>
            </td>
        </tr>
    @endforeach
@endif
