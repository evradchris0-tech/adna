<?php

namespace App\Livewire;

use App\Models\Associations;
use Livewire\Component;

class AssociationSelector extends Component
{
    public $activeAssociationId;
    public $showDropdown = false;

    protected $listeners = ['associationChanged' => '$refresh'];

    public function mount()
    {
        $this->initializeAssociation();
    }

    private function initializeAssociation()
    {
        // Récupérer l'association active depuis la session
        $this->activeAssociationId = session('active_association_id');

        // Si pas d'association active, prendre la première disponible
        if (!$this->activeAssociationId) {
            $associations = $this->getUserAssociations();

            if ($associations->isNotEmpty()) {
                $this->activeAssociationId = $associations->first()->id;
                session(['active_association_id' => $this->activeAssociationId]);
            }
        }
    }

    public function switchAssociation($associationId)
    {
        $associations = $this->getUserAssociations();
        $association = $associations->firstWhere('id', $associationId);

        if (!$association) {
            session()->flash('error', 'Association non accessible.');
            return;
        }

        $this->activeAssociationId = $associationId;
        session(['active_association_id' => $associationId]);

        session()->flash('message', 'Association changée avec succès !');

        $this->dispatch('associationChanged', $associationId);
    }


    private function getUserAssociations()
    {
        $user = auth()->user();

        // Vérifier si l'utilisateur est admin (adapter selon votre système)
        // Option 1: avec Spatie Permission
        if (method_exists($user, 'hasRole') && $user->hasRole('admin')) {
            return Associations::orderBy('name')->get();
        }

        // Option 2: avec un champ is_admin
        if (isset($user->is_admin) && $user->is_admin) {
            return Associations::orderBy('name')->get();
        }

        // Option 3: avec un champ role
        if (isset($user->role) && $user->role === 'admin') {
            return Associations::orderBy('name')->get();
        }

        // Pour les utilisateurs normaux, récupérer leurs associations
        if (method_exists($user, 'associations')) {
            return $user->associations()->orderBy('associations.name')->get();
        }

        // Si aucune relation n'existe, retourner une collection vide
        return collect();
    }

    private function hasMultipleAssociations()
    {
        return $this->getUserAssociations()->count() > 1;
    }

    public function render()
    {
        $userAssociations = $this->getUserAssociations();

        $activeAssociation = null;
        if ($this->activeAssociationId) {
            $activeAssociation = $userAssociations->firstWhere('id', $this->activeAssociationId);
        }

        return view('associations.livewire.association-selector', [
            'activeAssociation' => $activeAssociation,
            'userAssociations' => $userAssociations,
            'hasMultipleAssociations' => $this->hasMultipleAssociations(),
        ]);
    }
}
