<?php

namespace App\Http\Controllers;

use App\Models\Associations;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserAssociationsController extends Controller
{
    /**
     * Afficher les associations de l'utilisateur
     */
    public function index()
    {
        $user = auth()->user();
        
        // Associations de l'utilisateur avec informations pivot
        $userAssociations = $user->associations()
            ->with('paroissiens')
            ->get()
            ->map(function ($association) {
                return [
                    'id' => $association->id,
                    'name' => $association->name,
                    'sigle' => $association->sigle,
                    'is_primary' => $association->pivot->is_primary,
                    'role_in_association' => $association->pivot->role_in_association,
                    'paroissiens_count' => $association->paroissiens->count(),
                    'created_at' => $association->pivot->created_at,
                ];
            });

        // Associations disponibles (pour les admins uniquement)
        $availableAssociations = collect();
        if ($user->hasRole('admin')) {
            $availableAssociations = Associations::whereNotIn(
                'id', 
                $userAssociations->pluck('id')
            )->get();
        }

        return view('user.associations.index', compact('userAssociations', 'availableAssociations'));
    }

    /**
     * Attacher une association à l'utilisateur
     */
    public function attach(Request $request)
    {
        $request->validate([
            'association_id' => 'required|exists:associations,id',
            'role_in_association' => 'nullable|string|max:255',
            'is_primary' => 'boolean',
        ]);

        $user = auth()->user();

        // Vérifier si l'utilisateur a déjà cette association
        if ($user->hasAccessToAssociation($request->association_id)) {
            return back()->with('error', 'Vous êtes déjà membre de cette association.');
        }

        // Vérifier les permissions (seuls les admins peuvent s'auto-ajouter)
        if (!$user->hasRole('admin')) {
            return back()->with('error', 'Vous n\'avez pas la permission d\'ajouter des associations.');
        }

        DB::beginTransaction();
        try {
            $isPrimary = $request->boolean('is_primary', false);
            
            // Si c'est la principale, désactiver les autres
            if ($isPrimary) {
                $user->associations()->updateExistingPivot(
                    $user->associations->pluck('id')->toArray(),
                    ['is_primary' => false]
                );
            }

            // Attacher l'association
            $user->associations()->attach($request->association_id, [
                'is_primary' => $isPrimary,
                'role_in_association' => $request->role_in_association,
            ]);

            // Si c'est la première association, la définir comme active
            if ($user->associations()->count() === 1) {
                session(['active_association_id' => $request->association_id]);
            }

            DB::commit();
            return back()->with('message', 'Association ajoutée avec succès !');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Une erreur est survenue lors de l\'ajout de l\'association.');
        }
    }

    /**
     * Détacher une association de l'utilisateur
     */
    public function detach(Request $request)
    {
        $request->validate([
            'association_id' => 'required|exists:associations,id',
        ]);

        $user = auth()->user();

        // Vérifier que l'utilisateur a cette association
        if (!$user->hasAccessToAssociation($request->association_id)) {
            return back()->with('error', 'Vous n\'êtes pas membre de cette association.');
        }

        // Empêcher la suppression si c'est la seule association
        if ($user->associations()->count() === 1) {
            return back()->with('error', 'Vous ne pouvez pas retirer votre dernière association.');
        }

        DB::beginTransaction();
        try {
            $wasPrimary = $user->associations()
                ->where('association_id', $request->association_id)
                ->first()
                ->pivot
                ->is_primary;

            // Détacher l'association
            $user->associations()->detach($request->association_id);

            // Si c'était l'association principale, définir une autre comme principale
            if ($wasPrimary && $user->associations()->count() > 0) {
                $firstAssociation = $user->associations()->first();
                $user->associations()->updateExistingPivot(
                    $firstAssociation->id,
                    ['is_primary' => true]
                );
            }

            // Si c'était l'association active, changer pour la principale ou la première
            if (session('active_association_id') == $request->association_id) {
                $newActive = $user->primaryAssociation ?? $user->associations()->first();
                session(['active_association_id' => $newActive->id]);
            }

            DB::commit();
            return back()->with('message', 'Association retirée avec succès !');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Une erreur est survenue lors du retrait de l\'association.');
        }
    }

    /**
     * Définir une association comme principale
     */
    public function setPrimary(Request $request)
    {
        $request->validate([
            'association_id' => 'required|exists:associations,id',
        ]);

        $user = auth()->user();

        if (!$user->hasAccessToAssociation($request->association_id)) {
            return back()->with('error', 'Vous n\'avez pas accès à cette association.');
        }

        DB::beginTransaction();
        try {
            // Retirer le statut primary de toutes les associations
            $user->associations()->updateExistingPivot(
                $user->associations->pluck('id')->toArray(),
                ['is_primary' => false]
            );

            // Définir la nouvelle comme principale
            $user->associations()->updateExistingPivot(
                $request->association_id,
                ['is_primary' => true]
            );

            // Mettre à jour l'association active
            session(['active_association_id' => $request->association_id]);

            DB::commit();
            return back()->with('message', 'Association principale définie avec succès !');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Une erreur est survenue.');
        }
    }

    /**
     * Mettre à jour le rôle dans une association
     */
    public function updateRole(Request $request)
    {
        $request->validate([
            'association_id' => 'required|exists:associations,id',
            'role_in_association' => 'required|string|max:255',
        ]);

        $user = auth()->user();

        if (!$user->hasAccessToAssociation($request->association_id)) {
            return back()->with('error', 'Vous n\'avez pas accès à cette association.');
        }

        try {
            $user->associations()->updateExistingPivot(
                $request->association_id,
                ['role_in_association' => $request->role_in_association]
            );

            return back()->with('message', 'Rôle mis à jour avec succès !');
        } catch (\Exception $e) {
            return back()->with('error', 'Une erreur est survenue lors de la mise à jour du rôle.');
        }
    }
}