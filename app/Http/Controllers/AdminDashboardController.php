<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Log;
use App\Models\Tache;
use App\Models\Pointage;


class AdminDashboardController extends Controller
{
    public function index()
    {
        $employees = User::where('role', 'employe')->with(['pointages', 'taches' => function ($q) {
            $q->whereDate('created_at', today());
        }])->get();

        $data = [];

        foreach ($employees as $emp) {
            $pointage = $emp->pointages()->whereDate('created_at', today())->first();
            $tasksToday = $emp->taches->count();

            $arrive = $pointage ? $pointage->heure_arrivee : null;
            $depart = $pointage ? $pointage->heure_depart : null;

            $score = 0;

            if ($arrive && $arrive <= '08:00:00') $score += 5;
            if ($arrive && $depart && Carbon::parse($arrive)->diffInHours($depart) >= 8) $score += 5;
            $score += $tasksToday;

            $data[] = [
                'name' => $emp->name,
                'score' => $score,
            ];
        }
        return view('admin.accueiladmin', ['scores' => $data]);
    }

    public function consulterhistorique(Request $request)
    {
        $query = User::with(['pointages', 'taches'])
            ->where('created_by', Auth::id());

        // Si une recherche est saisie, on filtre
        if ($request->has('search') && $request->input('search') !== '') {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->whereRaw("CONCAT(name, ' ', first_name) LIKE ?", ["%{$search}%"])
                    ->orWhereRaw("CONCAT(first_name, ' ', name) LIKE ?", ["%{$search}%"])
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }


        // Pagination avec pointages et tâches déjà chargés
        $comptesCrees = $query->paginate(10)->withQueryString();

        return view('admin.consulterhistorique', compact('comptesCrees'));
    }

    // public function rechercherEmployes(Request $request)
    // {
    //     $search = $request->query('q');

    //     $employes = User::where(function ($query) use ($search) {
    //         $query->where('name', 'LIKE', "%{$search}%")
    //             ->orWhere('first_name', 'LIKE', "%{$search}%");
    //     })
    //         ->where('role', 'employe') // adapte selon ton système
    //         ->latest()
    //         ->get();

    //     // Retourner la partie HTML
    //     return response()->json([
    //         'htmlDesktop' => view('partials.resultats-desktop', compact('employes'))->render(),
    //         'htmlMobile' => view('partials.resultats-mobile', compact('employes'))->render()
    //     ]);
    // }

//     public function rechercherEmployes(Request $request)
// {
//     $search = $request->query('q');

//     $query = User::where('role', 'employe');

//     if (!empty($search)) {
//         $query->where(function ($q) use ($search) {
//             $q->where('name', 'LIKE', "%{$search}%")
//               ->orWhere('first_name', 'LIKE', "%{$search}%");
//         });
//     }

//     $employes = $query->latest()->get();

//     return response()->json([
//         'htmlDesktop' => view('partials.resultats-desktop', compact('employes'))->render(),
//         'htmlMobile' => view('partials.resultats-mobile', compact('employes'))->render()
//     ]);
// }


public function rechercherEmployes(Request $request)
{
    try {
        $search = $request->query('q');

        $query = User::where('role', 'employe');

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('first_name', 'LIKE', "%{$search}%");
            });
        }

        $employes = $query->latest()->get();

        return response()->json([
            'htmlDesktop' => view('partials.resultats-desktop', compact('employes'))->render(),
            'htmlMobile' => view('partials.resultats-mobile', compact('employes'))->render()
        ]);
    } catch (\Exception $e) {
        Log::error('Erreur recherche employés : ' . $e->getMessage());

        return response()->json([
            'htmlDesktop' => '<tr><td colspan="5" class="text-center text-red-600">Erreur serveur, veuillez réessayer plus tard.</td></tr>',
            'htmlMobile' => '<div class="text-center text-red-600">Erreur serveur, veuillez réessayer plus tard.</div>',
        ], 500);
    }
}


    public function redirigerNotification($id)
    {
        $notification = DatabaseNotification::findOrFail($id);

        // Marquer notification comme lue
        $notification->markAsRead();

        // Rediriger vers la page d’historique de l’employé
        $userId = $notification->data['user_id'];
        return redirect()->route('employe.historique', $userId);
    }

    // Activation/Désactiver d'un Compte
    public function toggleStatus(User $user)
    {
        $user->active = !$user->active;
        $user->save();
        return redirect()->back()->with('success', 'Statut modifié avec succès.');
    }

    // Ajouter : supprimer compte
    public function destroyUser(User $user)
    {
        $user->delete();
        return redirect()->back()->with('success', 'Compte supprimé avec succès.');
    }

    public function destroyAll()
    {
        // Supprimer tous les utilisateurs sauf l'admin connecté (optionnel)
        User::where('id', '!=', auth()->id())->delete();

        return redirect()->back()->with('success', 'Tous les comptes ont été supprimés avec succès.');
    }

    public function showEmployeHistorique(Request $request, User $user)
    {
        // Tâches paginées (exemple sans filtre ici)
        $taches = $user->taches()->latest()->paginate(10);

        // Pointages avec filtre par date ou mois
        $pointagesQuery = $user->pointages();

        if ($request->filled('date')) {
            // Filtre sur une date précise
            $pointagesQuery->whereDate('date_pointage', $request->input('date'));
        } elseif ($request->filled('month')) {
            // Filtre par mois (format attendu : YYYY-MM, ex: 2025-07)
            $month = $request->input('month');
            $pointagesQuery->whereYear('date_pointage', substr($month, 0, 4))
                ->whereMonth('date_pointage', substr($month, 5, 2));
        }

        $pointages = $pointagesQuery->latest()->paginate(10);


        return view('admin.employe_historique', compact('user', 'taches', 'pointages'));
    }

    public function supprimerTacheAdmin($id)
    {
        $tache = Tache::findOrFail($id);
        $tache->delete(); // suppression logique
        return redirect()->back()->with('success', 'Tâche supprimée avec succès par l\'admin.');
    }

    public function supprimerPointageAdmin($id)
    {
        $pointage = Pointage::findOrFail($id);
        $pointage->delete(); // suppression logique
        return redirect()->back()->with('success', 'Pointage supprimé avec succès par l\'admin.');
    }
}
