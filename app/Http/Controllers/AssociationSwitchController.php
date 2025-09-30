<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AssociationSwitchController extends Controller
{
    /**
     * Changer l'association active
     */
    public function switch(Request $request)
    {
        $request->validate([
            'association_id' => 'required|exists:associations,id'
        ]);

        $user = auth()->user();
        $associationId = $request->association_id;

        // Vérifier que l'utilisateur a accès à cette association
        if (!$user->hasAccessToAssociation($associationId)) {
            return back()->with('error', 'Vous n\'avez pas accès à cette association.');
        }

        // Changer l'association active en session
        session(['active_association_id' => $associationId]);

        return back()->with('message', 'Association changée avec succès !');
    }

    /**
     * Définir une association comme principale
     */
    public function setPrimary(Request $request)
    {
        $request->validate([
            'association_id' => 'required|exists:associations,id'
        ]);

        $user = auth()->user();
        $associationId = $request->association_id;

        if (!$user->hasAccessToAssociation($associationId)) {
            return back()->with('error', 'Vous n\'avez pas accès à cette association.');
        }

        $user->setPrimaryAssociation($associationId);
        session(['active_association_id' => $associationId]);

        return back()->with('message', 'Association principale définie avec succès !');
    }
}