<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pointage;
use App\Models\Tache;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Notifications\PointageNotification;
use App\Notifications\TacheEnvoyeeNotification;

use Illuminate\Support\Facades\Notification;

class EmployeDashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        // Récupérer tous les utilisateurs avec le nombre de tâches faites aujourd'hui
        $scores = User::where('role', 'employe')
            ->withCount(['taches as score' => function ($query) use ($today) {
                $query->whereDate('created_at', $today);
            }])->get();


        return view('employe.dash_employe', compact('scores'));
    }


    public function ShowTaskForm()
    {
        $userId = Auth::id();
        $today = now()->toDateString();

        // Vérifie si une tâche existe aujourd'hui pour cet utilisateur
        $taskExistsToday = Tache::where('user_id', $userId)
            ->whereDate('created_at', $today)
            ->exists();

        return view("employe.form_task", compact('taskExistsToday'));
    }


    public function HandleTask(Request $request)
    {
        $request->validate(
            [
                'description' => 'required|string|min:10',
            ],
            [
                'description.required' => 'La description est obligatoire.',
                'description.min' => 'La description doit contenir au moins 10 caractères.',
            ]
        );

        $userId = Auth::id();
        $today = now()->toDateString();

        // Vérifie si une tâche existe déjà aujourd'hui pour cet utilisateur
        $taskExists = Tache::where('user_id', $userId)
            ->whereDate('created_at', $today)
            ->exists();

        if ($taskExists) {
            return redirect()->back()->withErrors(['description' => 'Vous avez déjà soumis une tâche aujourd\'hui.']);
        }

        // ✅ Création de la tâche
        $tache = Tache::create([
            'description' => $request->description,
            'user_id' => $userId,
            'libelle_tache' => 'Tâche du ' . now()->format('d/m/Y'),
        ]);

        // ✅ Notification aux admins
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new TacheEnvoyeeNotification($tache));
        }

        return redirect()->back()->with("success", "Tâche enregistrée avec succès");
    }



    public function ShowAllTask()
    {
        $taches = Tache::where('user_id', Auth::id())
            ->where('masque_par_employe', false) // 👈 On masque côté employé
            ->orderBy('created_at', 'asc')
            ->paginate(10);

        return view("employe.tasklist", compact("taches"));
    }


    public function ShowPointage()
    {
        $userId = Auth::id();
        $today = now()->toDateString();

        $pointage = Pointage::where('user_id', $userId)
            ->whereDate('date_pointage', $today)
            ->first();

        // Flags pour les boutons
        $hasArrivee = $pointage && $pointage->heure_arrivee !== null;
        $hasDepart = $pointage && $pointage->heure_depart !== null;

        return view("employe.pointage_page", [
            'hasArrivee' => $hasArrivee,
            'hasDepart' => $hasDepart,
        ]);
    }

    // Nouvelle méthode pour supprimer une tâche
    public function destroyTask(Tache $tache)
    {
        if ($tache->user_id !== Auth::id()) {
            abort(403, 'Action non autorisée');
        }

        $tache->masque_par_employe = true;
        $tache->save();

        return back()->with('success', 'Tâche masquée avec succès');
    }


    public function HandlePointage(Request $request)
    {
        $heureReference = Carbon::createFromTime(10, 00); // 08:30

        $today = now()->toDateString();
        $userId = Auth::id();

        // Vérifie si un pointage existe déjà pour aujourd’hui
        $pointage = Pointage::where('user_id', $userId)
            ->whereDate('date_pointage', $today)
            ->first();

        if (!$pointage) {
            // Création d’un nouveau pointage
            $pointage = new Pointage();
            $pointage->user_id = $userId;
            $pointage->date_pointage = $today;
        }

        if ($request->type === "arrivee" && !$pointage->heure_arrivee) {
            $heureActuelle = Carbon::now();
            $pointage->heure_arrivee = $heureActuelle->format('H:i');

            if ($heureActuelle->gt($heureReference)) {
                // En retard
                $request->validate([
                    'motif_retard' => 'required|string|min:3',
                ]);
                $pointage->justificatif_retard = $request->motif_retard;
            }
        }


        if ($request->type === "depart" && !$pointage->heure_depart) {
            $pointage->heure_depart = Carbon::now()->format('H:i');
        }

        $pointage->statut = true;
        $pointage->save();

        // --- Envoi notification aux admins ---
        $admins = User::where('role', 'admin')->get();

        foreach ($admins as $admin) {
            $admin->notify(new PointageNotification(Auth::user(), $request->type, now()->format('H:i')));
        }

        return redirect()->back()->with("success", "Pointage effectué avec succès.");
    }

    public function ShowPointages()
    {
        $userId = Auth::id();
        $pointages = Pointage::where('user_id', $userId)
            ->where('hidden_by_employee', false) // 👈 Ne montre que les non masqués
            ->orderByDesc('date_pointage')
            ->paginate(10);

        return view('employe.list_pointages', compact('pointages'));
    }

    public function destroyPointage(Pointage $pointage)
    {
        if ($pointage->user_id !== auth()->id()) {
            abort(403);
        }

        $pointage->hidden_by_employee = true; // ✅ Masquage logique
        $pointage->save();

        return redirect()->back()->with('success', 'Pointage masqué avec succès.');
    }


    public function destroyAllPointages()
    {
        Pointage::where('user_id', auth()->id())->delete();
        return redirect()->back()->with('success', 'Tous les pointages ont été supprimés.');
    }
}
