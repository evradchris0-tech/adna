<div>
    <div class="body_header d_flex jc_between ai_center">
        <h1 class="body_header_title">Details versements</h1>

        <div class="btn_block d_flex ai_center">
            @can('versement.print')
                <button class="btn btn_main btn_outline" wire:click='downloadXlsx'>
                    <i class="fa fa-file-export"></i>
                    <span>Excel</span>
                    <span wire:loading wire:target='downloadXlsx'><i class="fa fa-spin fa-spinner"></i></span>
                </button>
                <button class="btn btn_main btn_outline" wire:click='downloadPdf'>
                    <i class="fa fa-file-export"></i>
                    <span>Pdf</span>
                    <span wire:loading wire:target='downloadPdf'><i class="fa fa-spin fa-spinner"></i></span>
                </button>
            @endcan
            @can('versement.create')
                <a href="{{ route('versement.create') }}" class="btn">
                    <i class="fa fa-plus"></i>
                    <span>Ajouter</span>
                </a>
            @endcan
        </div>
    </div>
    <div class="card">
        @livewire('Alert')
        <h2 class="body_sub_title">Informations du paroissien</h2>
        <table class="info_table">
            <thead>
                <tr>
                    <th>Ancien Matricule</th>
                    <th>Nom</th>
                    <th>Association</th>
                    <th>Situation</th>
                    <th>Categorie</th>
                    <th>Niveau d'etude</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><span>{{ $paroissien->old_matricule }}</span></td>
                    <td><span>{{$paroissien->firstname}} {{$paroissien->lastname}}</span></td>
                    <td><span>{{ $paroissien->association->name }}</span></td>
                    <td><span>{{ $paroissien->situation }}</span></td>
                    <td><span>{{ $paroissien->categorie }}</span></td>
                    <td><span>{{ $paroissien->school_level }}</span></td>
                </tr>
            </tbody>
        </table>
        <h2 class="body_sub_title">Informations des versements</h2>
        <div class="card_header filters">
            <div class="form_group">
                <label for="type">Type</label>
                <div class="form_input">
                    <select name="type" id="type" wire:model="type">
                        <option value="" selected>Type</option>
                        <option value="dime" >Dime</option>
                        <option value="dette_dime" >Dette dime</option>
                        <option value="dette_cotisation" >Dette cotisation</option>
                        <option value="Offrande de construction" >Offrande de construction</option>
                    </select>
                </div>
            </div>
            <div class="form_group">
                <label for="type">Apres le</label>
                <div class="form_input">
                    <input  type="date" placeholder="Rechercher..." wire:model="periode_start">
                </div>
            </div>
            <div class="form_group">
                <label for="type">Avant le</label>
                <div class="form_input">
                    <input  type="date" placeholder="Rechercher..." wire:model="periode_end">
                </div>
            </div>
            <button class="btn btn_dark" wire:click='$refresh'>
                <span>Filtrer</span>
            </button>
        </div>
        <div class="card_content">
            <table>
                <thead>
                    <tr>
                        <th>N°</th>
                        <th>Type</th>
                        <th>Somme</th>
                        <th>Date versement</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($versements as $versement)
                        <tr>
                            <td><span>{{ $versement->id }}</span></td>
                            <td><span>{{ $versement->type }}</span></td>
                            <td><span>{{ formatNumber($versement->somme )}}</span></td>
                            <td><span>{{ $versement->created_at }}</span></td>
                            <td>
                                <div class="t_action">
                                    @can('versement.update')
                                        <a href="{{ route('versement.update', $versement->id) }}"
                                            class="update btn_action"> <i class="fa fa-pencil"></i> </a>
                                    @endcan
                                    @can('versement.delete')
                                        <div class="update btn_action"
                                            wire:click='onSHowDeleteModal({{ $versement->id }})'> <i
                                                class="fa fa-trash"></i> </div>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $versements->links() }}
        </div>
    </div>


    <div class="modal_wrapper modal1 {{ $showModal ? 'show' : '' }}">
        <div class="card modal">
            <div class="modal_header">
                <h1>Suppression d'un versement</h1>
                <i class="fa fa-plus js_cross" wire:click='onSHowDeleteModal'></i>
            </div>
            <div class="modal_body">
                <p>Souhaitez-vous vraiment supprimer ce versement?</p>

                <div class="btn_block add_block d_flex ai_center jc_center w_100">
                    <button class="btn btn_main btn_outline js_abort" wire:click='onSHowDeleteModal'>
                        <span>Annuler</span>
                    </button>
                    <button class="btn" wire:click='destroy' wire:loading.attr='disabled'>
                        <span>Confirmer</span>
                        <span wire:loading wire:target='destroy'><i class="fa fa-spin fa-spinner"></i></span>
                    </button>
                </div>
            </div>
        </div>
    </div>



</div>
