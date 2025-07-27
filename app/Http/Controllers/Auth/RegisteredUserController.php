<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendPasswordToEmployee;


class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
        'first_name' => ['required', 'string', 'min:2', 'max:50', 'regex:/^[A-Za-zÀ-ÖØ-öø-ÿ\s\-]+$/'],
        'name' => ['required', 'string', 'min:2', 'max:50', 'regex:/^[A-Za-zÀ-ÖØ-öø-ÿ\s\-]+$/'],
        'email' => ['required', 'email', 'max:255', 'unique:users,email', 'email:rfc,dns'],
        // 'password' => ['required', 'confirmed', 'min:8'], // Si tu ajoutes le mot de passe
    ]);

        // Génération automatique du mot de passe
        $generatedPassword = Str::random(10);

        $user = User::create([
            'first_name' => $request->first_name,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($generatedPassword),
            'role' => 'employe',
            'created_by' => Auth::id(), // 👈 admin actuellement connecté
        ]);

        // Envoi du mot de passe par email
        Mail::to($user->email)->send(new SendPasswordToEmployee($user, $generatedPassword));

        event(new Registered($user));

        return redirect()->route('admin.register')->with('status', 'Compte employé créé avec succès.');
    }
}
