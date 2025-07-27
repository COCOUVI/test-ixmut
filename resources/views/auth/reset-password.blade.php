@extends('layouts.welcome')
@section('content')
<div class="max-w-md mx-auto my-12 p-8 rounded-xl shadow-md bg-dark text-white">
    <!-- Logo -->
    <div class="flex justify-center mb-6">
        <img src="{{ asset('images/Logo IMUXT (Blanc).png') }}" alt="Logo IMUXT" class="h-10 w-auto" />
    </div>

    @if ($errors->any())
    <div class="mb-4 p-4 bg-red-200 text-red-700 rounded">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


    <form method="POST" action="{{ route('password.store') }}" novalidate>
        @csrf
        {{-- <input type="hidden" name="token" value="{{ old('token', $request->input('token')) }}"> --}}
        <input type="hidden" name="token" value="{{ old('token', $token) }}">



        <!-- Email Address -->
        <div class="mb-6">
            <label for="email" class="block font-semibold mb-2">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username"
                class="w-full px-4 py-3 rounded-md border border-gray-300 text-dark focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent" />
            @error('email')
            <p class="mt-2 text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-6">
            <label for="password" class="block font-semibold mb-2">Mot de passe</label>
            <input id="password" type="password" name="password" required autocomplete="new-password"
                class="w-full px-4 py-3 rounded-md border border-gray-300 text-dark focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent" />
            @error('password')
            <p class="mt-2 text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div class="mb-6">
            <label for="password_confirmation" class="block font-semibold mb-2">Confirmer le mot de passe</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                class="w-full px-4 py-3 rounded-md border border-gray-300 text-dark focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent" />
            @error('password_confirmation')
            <p class="mt-2 text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <button type="submit"
                class="w-full px-6 py-3 bg-primary text-dark font-semibold rounded-md shadow hover:bg-white/90 focus:outline-none focus:ring-2 focus:ring-primary transition">
                Réinitialiser le mot de passe
            </button>
        </div>
    </form>
</div>
@endsection
