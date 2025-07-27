<?php

namespace App\Http\Controllers;

use App\Models\Pointage;
use Illuminate\Http\Request;

class PointageController extends Controller
{
    // Supprimer un pointage
    public function destroy(Pointage $pointage)
    {
        // Marquer le pointage comme masqué par l'employé
        $pointage->hidden_by_employee = true;
        $pointage->save();

        return redirect()->back()->with('success', 'Pointage masqué avec succès.');
    }

    public function destroyAsAdmin($id)
    {
        $pointage = Pointage::withTrashed()->findOrFail($id);
        $pointage->delete(); // soft delete = masque pour admin

        return redirect()->back()->with('success', 'Pointage masqué pour l\'admin.');
    }

    public function masquerMultiple(Request $request)
{
    $ids = $request->input('pointages', []);

    foreach ($ids as $id) {
        $pointage = Pointage::find($id);
        if ($pointage) {
            $pointage->delete(); // soft delete
        }
    }

    return redirect()->back()->with('success', 'Tâches sélectionnées masquées avec succès.');
}


}
