<div>
    <div class="body_header d_flex jc_between ai_center">
        <h1 class="body_header_title">Offrandes de {{$this->association->name}}</h1>

        <div class="btn_block d_flex ai_center">
            @can("association.print")
                <button  class="btn btn_main btn_outline" wire:click='download'>
                    <i class="fa fa-file-export"></i>
                    <span>Exporter</span>
                </button>
            @endcan
            @can("association.create")
                <button  class="btn js_new" wire:click='onShowModal'>
                    <i class="fa fa-plus"></i>
                    <span>Ajouter</span>
                </button>
            @endcan
        </div>
    </div>
    <div class="card">
        <div class="card_header filters">
            <div class="form_group form_search">
                <div class="form_input" >
                    <input  type="text" placeholder="Rechercher..." wire:model.live.debounce.500ms="term">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </div>
            </div>
        </div>
        <div class="card_content">
            @livewire('Alert')
            <table>
                <thead>
                    <tr>
                      <x-table-th :direction="$orderDirection" label="N°" name="id" :field="$orderField">N°</x-table-th>
                      <x-table-th :direction="$orderDirection" label="Somme encaissée" name="somme" :field="$orderField">Somme encaissée</x-table-th>
                      <x-table-th :direction="$orderDirection" label="Créer le" name="offrande_day" :field="$orderField">Créer le</x-table-th>
                      <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                        @foreach ($offrandes as $off)
                            <tr>
                                <td>
                                    <span>{{$off->id}}</span>
                                </td>
                                <td><span>{{formatNumber($off->somme)}}</span></td>
                                <td><span>{{$off->offrande_day}}</span></td>
                                <td>
                                    <div class="t_action">
                                        @can("association.offrande.update")
                                            <span class="disabled btn_action" wire:click='onShowModal({{$off->id}})'><i class="fa fa-pencil"></i></span>
                                        @endcan
                                        @can("association.offrande.delete")
                                            <span class="disabled btn_action" wire:click='onSHowDeleteModal({{$off->id}})' ><i class="fa fa-trash"></i></span>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                </tbody>
            </table>
            {{ $offrandes->links() }}
        </div>
    </div>

    <div class="modal_wrapper modal1 {{ $showDeleteModal ? 'show' : '' }}">
        <div class="card modal">
            <div class="modal_header">
                <h1>Suppression d'une offrande</h1>
                <i class="fa fa-plus js_cross" wire:click='onSHowDeleteModal'></i>
            </div>
            <div class="modal_body">
                <p>Souhaitez-vous vraiment supprimer cette offrande?</p>

                <div class="btn_block add_block d_flex ai_center jc_center w_100">
                    <button class="btn btn_main btn_outline js_abort" wire:click='onSHowDeleteModal'>
                        <span>Annuler</span>
                    </button>
                    <button class="btn" wire:click='delete' wire:loading.attr='disabled'>
                        <span>Confirmer</span>
                        <span wire:loading wire:target='delete'><i class="fa fa-spin fa-spinner"></i></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal_wrapper {{$showModal ? 'show' : ''}}">
        <div class="card modal">
            <div class="modal_header">
                <h1>{{$id ? "Mise à jour de l'offrande" : "Nouvelle offrande"}}</h1>
                <i class="fa fa-plus js_cross" wire:click='onShowModal'></i>
            </div>
            <div class="modal_body">
                <form action="" wire:submit.prevent='onSubmit'>
                    <div class="form_group">
                        <label for="sommeOffrande">Somme offrande</label>
                        <div class="form_input">
                            <input type="text" name="sommeOffrande" wire:model='somme' required id="sommeOffrande" placeholder="Entrer la somme">
                        </div>
                        @error('somme') <span class="error">{{ $message }}</span> @enderror
                    </div>
                    <div class="form_group">
                        <label for="dateOffrande">Date offrande</label>
                        <div class="form_input">
                            <input type="date" name="dateOffrande" wire:model='offrande_day' required id="dateOffrande" >
                        </div>
                        @error('offrande_day') <span class="error">{{ $message }}</span> @enderror
                    </div>

                    <div class="btn_block add_block d_flex ai_center jc_center w_100">
                        <a href="#" class="btn btn_main btn_outline js_abort" wire:click='onShowModal'>
                            <span>Annuler</span>
                        </a>
                        <button class="btn" type="submit">
                            <span>{{$id ? "Modifier" : "Ajouter"}}</span>
                            <span wire:loading wire:target='onSubmit'><i class="fa fa-spin fa-spinner"></i></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
