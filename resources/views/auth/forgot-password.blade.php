@extends('layouts.welcome')
@section('content')
    <div class="max-w-md mx-auto my-16 p-10 rounded-xl shadow-[0_0_15px_rgba(22,79,99,0.7)] bg-[#164f63] text-white font-sans">
        <!-- Logo -->
        <div class="flex justify-center mb-8">
            <img src="{{ asset('images/Logo IMUXT (Blanc).png') }}" alt="Logo IMUXT" class="h-12 w-auto" />
        </div>

        <p class="mb-8 text-center leading-relaxed tracking-wide">
            Vous avez oublié votre mot de passe ? Pas de souci. Indiquez simplement votre adresse email et nous vous
            enverrons un lien pour réinitialiser votre mot de passe afin que vous puissiez en choisir un nouveau.
        </p>

        <!-- Session Status -->
        @if (session('status'))
            <div class="mb-6 p-4 bg-green-700 bg-opacity-90 text-white rounded-lg shadow-md border border-green-500">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" novalidate>
            @csrf

            <!-- Email Address -->
            <div class="mb-6">
                <label for="email" class="block font-semibold mb-3 tracking-wide">Email</label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    placeholder="votre.email@example.com"
                    class="w-full px-5 py-3 rounded-lg bg-[#164f63] bg-opacity-20 border border-white border-opacity-30 text-white placeholder-white placeholder-opacity-60
                           focus:outline-none focus:ring-2 focus:ring-[#ffffff] focus:ring-opacity-80 focus:border-transparent
                           shadow-inner shadow-[#ffffff33]
                           transition duration-300 ease-in-out"
                />
                @error('email')
                    <p class="mt-2 text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button with same width as input -->
            <div>
                <button
                    type="submit"
                    class="w-full px-6 py-3 rounded-lg bg-gradient-to-r from-[#0ea5e9] via-[#164f63] to-[#0ea5e9] hover:from-[#1e40af] hover:to-[#2563eb] text-white font-semibold shadow-lg
                           transition duration-300 ease-in-out transform hover:scale-105 focus:outline-none focus:ring-4 focus:ring-[#0ea5e9] focus:ring-opacity-70"
                >
                    Envoyer
                </button>
            </div>
        </form>
    </div>
@endsection
