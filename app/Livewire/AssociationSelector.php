<?php

namespace App\Livewire;

use App\Models\Associations;
use Livewire\Component;

class AssociationSelector extends Component
{
    public $activeAssociationId;
    public $userAssociations;
    public $showDropdown = false;

    public function mount()
    {
        $user = auth()->user();
        
        // Récupérer les associations de l'utilisateur
        if ($user->hasRole('admin')) {
            $this->userAssociations = Associations::all();
        } else {
            $this->userAssociations = $user->associations;
        }

        // Association active
        $this->activeAssociationId = session('active_association_id');
        
        // Si pas d'association active et qu'il y en a au moins une
        if (!$this->activeAssociationId && $this->userAssociations->count() > 0) {
            $this->activeAssociationId = $this->userAssociations->first()->id;
            session(['active_association_id' => $this->activeAssociationId]);
        }
    }

    public function switchAssociation($associationId)
    {
        $user = auth()->user();

        // Vérifier l'accès
        if (!$user->hasRole('admin') && !$user->hasAccessToAssociation($associationId)) {
            session()->flash('error', 'Accès refusé à cette association.');
            return;
        }

        // Changer l'association active
        $this->activeAssociationId = $associationId;
        session(['active_association_id' => $associationId]);
        
        $this->showDropdown = false;
        
        session()->flash('message', 'Association changée avec succès !');
        
        // Rafraîchir la page pour appliquer le changement
        return redirect()->to(request()->header('Referer'));
    }

    public function toggleDropdown()
    {
        $this->showDropdown = !$this->showDropdown;
    }

    public function render()
    {
        $activeAssociation = Associations::find($this->activeAssociationId);
        
        return view('livewire.association-selector', [
            'activeAssociation' => $activeAssociation,
        ]);
    }
}