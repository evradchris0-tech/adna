<?php

namespace App\Livewire;

use Livewire\Component;

class MultiSelectComponent extends Component
{
    public $selectedVals = [];
    public $datas = [];
    protected $listeners = ['resetData'];

    public function mount($datas,$selected){
        $this->selectedVals = $selected;
        $this->datas = $datas;
    }
    public function resetData($d){
        $this->selectedVals = $d;
    }
    public function updatedSelected($data){}


    public function render()
    {
        return view('components.multi-select-component');
    }
}
