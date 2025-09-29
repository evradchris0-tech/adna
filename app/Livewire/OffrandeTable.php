<?php

namespace App\Livewire;

use App\Models\Associations;
use App\Models\Offrande;
use Livewire\Component;
use Livewire\WithPagination;

class OffrandeTable extends Component
{
    use WithPagination;

    public string $term = '';
    public string $orderField = "somme";
    public string $orderDirection = "ASC";
    public bool $showModal = false;
    public bool $showDeleteModal = false;
    public bool $isSending = false;
    public bool $isDeleting = false;
    public bool $showImportModal = false;
    public int $numPerPage = 30;
    public $id = null;
    public $associationId = null;
    public  $somme = 0;
    public  $offrande_day = null;
    public  $association = null;

    public function mount(int $id = 0){
        if ($id || $id != 0) {
            $this->associationId = $id;
            $this->association = Associations::find($id);
        }
    }

    public function setOrderField(string $field){
        if ($field == $this->orderField) {
            $this->orderDirection = $this->orderDirection  == 'ASC' ? 'DESC' : 'ASC';
        } else {
            $this->orderField = $field;
            $this->reset('orderDirection');
        }

    }


    public function paginationView()
    {
        return 'components.pagination';
    }

    public function onShowModal($id = null){
        if ($this->isSending) {
            return;
        }
        if ($id != null) {
            $offrande = Offrande::find($id);
            $this->somme = $offrande->somme;
            $this->offrande_day = $offrande->offrande_day;
            $this->id = $id;
        }else{
            $this->reset(['somme','offrande_day','id']);
        }
        $this->showModal = !$this->showModal;
        $this->resetErrorBag();
    }

    public function onSubmit(){
        if ($this->isSending) {
            return;
        }

        $this->isSending = true;
        if ($this->id != null) {
            $offrand = Offrande::find($this->id);
            $offrand->somme = $this->somme;
            $offrand->offrande_day = $this->offrande_day;
            $offrand->save();
        }else{
            Offrande::create([
                'somme' => $this->somme,
                'association_id' => $this->associationId,
                'offrande_day' => $this->offrande_day,
            ]);
        }
        $this->isSending = false;
        $this->showModal = !$this->showModal;
        $this->reset(['somme','offrande_day','id']);
        $this->resetErrorBag();
        $this->render();
    }

    public function onSHowDeleteModal($id=null){
        if ($this->isDeleting) {
            return;
        }
        $this->id = $id;
        $this->showDeleteModal = !$this->showDeleteModal;
    }
    public function delete(){
        $this->isDeleting = true;
        $association = Offrande::find($this->id);
        $association->delete();
        session()->flash('message',"Offrandes suprimé!");
        $this->reset(['id','showDeleteModal',"isDeleting"]);
        $this->render();
    }

    public function download(){
        $associations = Offrande::with("associations")->get();
        $filename = "offrandes.csv";
        $handle = fopen($filename, 'w');
        fputcsv($handle, [
            "id",
            "nom-association",
            "somme",
            "date-creation"
        ]);

        foreach ($associations as $row) {
            fputcsv($handle, array($row['id'],$row['associations']['name'], $row['somme'], $row['offrande_day']));
        }
        fclose($handle);

        // headers used to make the file "downloadable", we set them manually
        // since we can't use Laravel's Response::download() function
        $headers = array(
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="offrandes.csv"',
        );

        return response()->download($filename, 'offrandes.csv', $headers);
    }

    public function render()
    {
        $offrandes = Offrande::with("associations")->where("association_id",$this->associationId)->paginate(10);
        return view('associations.offrande.livewire.offrande-table', compact("offrandes"));
    }

    public function onShowImportModal(){
        if ($this->isSending) return;
        $this->showImportModal = !$this->showImportModal;
    }
}
