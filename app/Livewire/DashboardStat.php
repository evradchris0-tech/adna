<?php

namespace App\Livewire;

use App\Models\Associations;
use Livewire\Component;

class DashboardStat extends Component
{
    public string $type = "dime";
    public function render()
    {
        $assoStat = $this->getAssociationPaiementStat();
        return view('components/dashboard-stat',compact("assoStat"));
    }

    public function getAssociationPaiementStat(){
        $type = $this->type;
        $assoStat = Associations::getPaiementStat($type);
        return $assoStat;
    }
}
