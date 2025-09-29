<?php

namespace App\Livewire;

use Livewire\Component;

class Alert extends Component
{
    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.alert');
    }

    public function removeAlert(){
        session()->remove("message");
        session()->remove("error");
        session()->remove("errors");
    }
}
