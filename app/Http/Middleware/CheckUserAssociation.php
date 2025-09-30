<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserAssociation
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        // Les admins ont accès à tout
        if ($user->hasRole('admin')) {
            return $next($request);
        }

        // Vérifier si l'utilisateur a au moins une association
        if ($user->associations()->count() === 0) {
            abort(403, 'Vous n\'êtes assigné à aucune association.');
        }

        // Stocker l'association active en session si elle n'existe pas
        if (!session()->has('active_association_id')) {
            $primaryAssociation = $user->primaryAssociation()->first();
            if ($primaryAssociation) {
                session(['active_association_id' => $primaryAssociation->id]);
            } else {
                // Utiliser la première association disponible
                $firstAssociation = $user->associations()->first();
                session(['active_association_id' => $firstAssociation->id]);
            }
        }

        // Vérifier que l'utilisateur a accès à l'association active
        $activeAssociationId = session('active_association_id');
        if (!$user->hasAccessToAssociation($activeAssociationId)) {
            // Réinitialiser avec une association valide
            $firstAssociation = $user->associations()->first();
            session(['active_association_id' => $firstAssociation->id]);
        }

        return $next($request);
    }
}