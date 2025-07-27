<?php

namespace App\Http\Controllers;

use App\Models\Tache;
use Illuminate\Http\Request;

class TacheController extends Controller
{
    // Supprimer une tâche
    public function destroy(Tache $tache)
    {
        $tache->delete();
        return redirect()->back()->with('success', 'Tâche supprimée avec succès.');
    }

    public function destroyAsAdmin($id)
    {
        $tache = Tache::withTrashed()->findOrFail($id);
        $tache->delete(); // soft delete = masque pour admin

        return redirect()->back()->with('success', 'Tâche masquée pour l\'admin.');
    }

    public function masquerMultiple(Request $request)
{
    $ids = $request->input('taches', []);

    foreach ($ids as $id) {
        $tache = Tache::find($id);
        if ($tache) {
            $tache->delete(); // soft delete
        }
    }

    return redirect()->back()->with('success', 'Tâches sélectionnées masquées avec succès.');
}

}
