@extends('layouts.master')

@section('title', 'Profil')
@section('page-title', 'Mon Profil')

@section('content')
    <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto">
            {{-- Message flash animé --}}
            @if (session('status') === 'profile-updated')
                <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show"
                    x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 -translate-x-full"
                    x-transition:enter-end="opacity-100 translate-x-0" x-transition:leave="transition ease-in duration-500"
                    x-transition:leave-start="opacity-100 translate-x-0" x-transition:leave-end="opacity-0 -translate-x-full"
                    class="mb-6 p-4 bg-green-100 text-green-700 rounded-lg font-medium shadow text-center">
                    ✅ Profil mis à jour avec succès.
                </div>
            @endif

            <div class="bg-dark/90 text-white rounded-2xl shadow-xl p-8">
                <h2 class="font-bold mb-6 border-b border-white/20 pb-2 text-center">Modifier mes informations</h2>

                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PATCH')

                    <!-- Nom -->
                    <div>
                        <label for="name" class="block font-semibold">Nom</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}"
                            class="mt-1 w-full rounded-md bg-white/10 border border-white/30 text-white placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-white focus:border-white transition px-4 py-2"
                            placeholder="Nom" required>
                        @error('name')
                            <span class="flex items-center p-2 space-x-2">
                                <i class="fas fa-exclamation-circle text-red-600 w-5"></i>
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            </span>
                        @enderror
                    </div>

                    <!-- Prénom -->
                    <div>
                        <label for="first_name" class="block font-semibold">Prénom</label>
                        <input type="text" id="first_name" name="first_name"
                            value="{{ old('first_name', $user->first_name) }}"
                            class="mt-1 w-full rounded-md bg-white/10 border border-white/30 text-white placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-white focus:border-white transition px-4 py-2"
                            placeholder="Prénom" required>
                        @error('first_name')
                            <span class="flex items-center p-2 space-x-2">
                                <i class="fas fa-exclamation-circle text-red-600 w-5"></i>
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            </span>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block font-semibold">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}"
                            class="mt-1 w-full rounded-md bg-white/10 border border-white/30 text-white placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-white focus:border-white transition px-4 py-2"
                            placeholder="Email" required>
                        @error('email')
                            <<span class="flex items-center p-2 space-x-2">
                                <i class="fas fa-exclamation-circle text-red-600 w-5"></i>
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            </span>
                        @enderror
                    </div>

                    <!-- Mot de passe -->
                    <div>
                        <label for="password" class="block font-semibold">Nouveau mot de passe
                            <span class="text-xs text-gray-300">(laisser vide pour ne pas changer)</span>
                        </label>
                        <input type="password" id="password" name="password"
                            class="mt-1 w-full rounded-md bg-white/10 border border-white/30 text-white placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-white focus:border-white transition px-4 py-2"
                            placeholder="Nouveau mot de passe">
                        @error('password')
                            <span class="flex items-center p-2 space-x-2">
                                <i class="fas fa-exclamation-circle text-red-600 w-5"></i>
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            </span>


                        @enderror
                    </div>

                    <!-- Confirmation -->
                    <div>
                        <label for="password_confirmation" class="block font-semibold">Confirmer le mot de
                            passe</label>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                            class="mt-1 w-full rounded-md bg-white/10 border border-white/30 text-white placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-white focus:border-white transition px-4 py-2"
                            placeholder="Confirmation">
                    </div>

                    <!-- Photo -->
                    <div>
                        <label for="profile_photo" class="block font-semibold">Photo de profil</label>
                        <input type="file" name="profile_photo" id="profile_photo"
                            class="mt-2 w-full text-sm text-white bg-white/10 border border-white/30 rounded-md focus:outline-none focus:ring-2 focus:ring-white focus:border-white transition px-4 py-2 file:bg-white/20 file:text-white file:rounded file:border-0 file:px-3 file:py-1">
                        @if ($user->profile_photo)
                            <div class="mt-4">
                                <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="Photo actuelle"
                                    class="h-16 w-16 rounded-full object-cover border-2 border-white">
                            </div>
                        @endif
                        @error('profile_photo')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Bouton -->
                    <div class="pt-4">
                        <button type="submit"
                            class="w-full justify-center sm:w-auto px-6 py-2 bg-white text-[#164f63] font-semibold rounded-md hover:bg-gray-100 transition shadow-lg flex items-center gap-2">
                            <i class="fas fa-save w-5 text-[#164f63]"></i>
                            Enregistrer les modifications
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
