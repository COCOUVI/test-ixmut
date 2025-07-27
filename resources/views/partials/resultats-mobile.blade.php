@if($employes->isEmpty())
    <div class="text-center text-gray-500 italic py-4">
        Aucun employé trouvé pour ce critère.
    </div>
@else
    @foreach ($employes as $user)
        <div class="bg-[#164f63] bg-opacity-10 rounded-xl p-5 shadow-lg border border-[#164f63]/30 cursor-pointer"
            onclick="toggleMobileDetails({{ $user->id }})">
            <p class="text-[#164f63] font-semibold mb-1"><span class="font-bold">Nom :</span> {{ $user->name }} {{ $user->first_name }}</p>
            <p class="text-[#164f63] mb-1"><span class="font-bold">Email :</span> {{ $user->email }}</p>
            <p class="text-[#164f63] mb-1"><span class="font-bold">Date création :</span> {{ $user->created_at->format('d/m/Y H:i') }}</p>
            <p class="mb-3">
                <span class="font-bold text-[#164f63]">Statut :</span>
                @if ($user->active)
                    <span class="inline-block px-3 py-1 rounded-full bg-green-100 text-green-700 font-semibold tracking-wide">Actif</span>
                @else
                    <span class="inline-block px-3 py-1 rounded-full bg-red-100 text-red-700 font-semibold tracking-wide">Désactivé</span>
                @endif
            </p>

            <div id="mobile-details-{{ $user->id }}" class="hidden mt-2 text-sm text-gray-700">
                <p class="font-semibold mb-1 mt-4">Pointages journaliers :</p>
                <ul class="list-disc pl-5 text-sm text-gray-700 mb-4">
                    @forelse ($user->pointages as $pointage)
                        <li>
                            {{ $pointage->date_pointage }} : {{ substr($pointage->heure_arrivee, 0, 5) }}
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
                                {{ $task->description }} -
                                <small>A : {{ \Carbon\Carbon::parse($task->created_at)->format('H:i:s') }}</small>
                            </span>
                        </li>
                    @empty
                        <li>Aucune tâche enregistrée.</li>
                    @endforelse
                </ul>
            </div>

            <div class="flex flex-wrap gap-3 justify-center">
                <a href="{{ route('employe.historique', $user->id) }}"
                   class="bg-[#164f63] hover:bg-[#0f3a4c] text-white px-4 py-2 rounded-lg font-semibold text-sm shadow-md transition w-full sm:w-auto text-center">
                    Voir détails
                </a>

                <form action="{{ route('admin.user.toggle', $user->id) }}" method="POST" class="w-full sm:w-auto">
                    @csrf
                    <button type="submit"
                            class="w-full bg-yellow-400 hover:bg-yellow-500 text-[#164f63] font-semibold px-4 py-2 rounded-lg text-sm shadow-md transition">
                        {{ $user->active ? 'Désactiver' : 'Activer' }}
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
