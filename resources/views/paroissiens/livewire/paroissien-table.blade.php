<div>
    <div class="body_header d_flex jc_between ai_center">
        <h1 class="body_header_title">Paroissiens</h1>

        <div class="btn_block d_flex ai_center">
            @can('paroissiens.print')
                <label class="btn btn_main btn_outline" wire:click='onShowImportModal()'>
                    <i class="fa fa-file-export"></i>
                    <span>Importer</span>
                </label>
                <button  class="btn btn_main btn_outline" wire:click='downloadXlsx'>
                    <i class="fa fa-file-export"></i>
                    <span>Excel</span>
                    <span wire:loading wire:target='downloadXlsx'><i class="fa fa-spin fa-spinner"></i></span>
                </button>
                <button  class="btn btn_main btn_outline" wire:click='downloadPdf'>
                    <i class="fa fa-file-export"></i>
                    <span>Pdf</span>
                    <span wire:loading wire:target='downloadPdf'><i class="fa fa-spin fa-spinner"></i></span>
                </button>
            @endcan
            @can('paroissiens.create')
                <a href="{{route('paroissiens.create')}}" class="btn">
                    <i class="fa fa-plus"></i>
                    <span>Ajouter</span>
                </a>
            @endcan
        </div>
    </div>
    <div class="card">
        @livewire('Alert')
        <div class="card_header filters">
            <div class="form_group form_search">
                <div class="form_input" >
                    <input  type="text" placeholder="Rechercher..." wire:model.live.debounce.500ms="term">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </div>
            </div>
        </div>
        <div class="card_header filters">
            <div class="form_group">
                <label for="association">Association</label>
                <div class="form_input">
                    <select name="association" id="association" wire:model="association">
                        <option value="" selected>Association</option>
                        @foreach ($associations as $asso)
                            <option value="{{$asso->id}}">{{$asso->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form_group">
                <label for="situation">Situation</label>
                <div class="form_input">
                    <select name="situation" id="situation" wire:model="situation">
                        <option value="" selected>Situation</option>
                        <option value="Sans emploi">Sans emploi</option>
                        <option value="Elève / Etudiant">Elève / Etudiant</option>
                        <option value="Employer">Employer</option>
                        <option value="Retraité">Retraité</option>
                        <option value="Invalide">Invalide</option>
                        <option value="Décédé">Décédé</option>
                    </select>
                </div>
            </div>
            <div class="form_group">
                <label for="categorie">Categorie</label>
                <div class="form_input">
                    <select name="categorie" id="categorie" wire:model="categorie">
                        <option value="" selected>Categorie</option>
                        <option value="Pasteur" >Pasteur</option>
                        <option value="Ancien" >Ancien</option>
                        <option value="Diacre" >Diacre</option>
                        <option value="Fidele" >Fidèle</option>
                    </select>
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
                    <x-table-th :direction="$orderDirection" label="Ancien mat" name="old_matricule" :field="$orderField">Ancien mat</x-table-th>
                    <x-table-th :direction="$orderDirection" label="Nouveau mat" name="new_matricule" :field="$orderField">Nouveau mat</x-table-th>
                    <x-table-th :direction="$orderDirection" label="Nom" name="firstname" :field="$orderField">Nom</x-table-th>
                    <x-table-th :direction="$orderDirection" label="Catégorie" name="categorie" :field="$orderField">Catégorie</x-table-th>
                    <x-table-th :direction="$orderDirection" label="Association" name="association_id" :field="$orderField">Association</x-table-th>
                    <x-table-th :direction="$orderDirection" label="Situation" name="situation" :field="$orderField">Situation</x-table-th>
                    <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($paroissiens as $paroissien)
                        <tr>
                            <td>
                                <span>{{$paroissien->old_matricule}}</span>
                            </td>
                            <td>
                                <span>{{$paroissien->new_matricule}}</span>
                            </td>
                            <td><span>{{$paroissien->firstname}} {{$paroissien->lastname}}</span></td>
                            <td><span>{{$paroissien->categorie}}</span></td>
                            <td><span>{{$paroissien->association->name}}</span></td>
                            <td><span>{{$paroissien->situation}}</span></td>
                            <td>
                                <div class="t_action">
                                    @can('paroissiens.show')
                                        <a href = "{{route('paroissiens.show', $paroissien->id)}}" class="update btn_action"><i class="fa fa-eye"></i></a>
                                    @endcan
                                    {{-- @can('paroissiens.lock')
                                        <span class="update btn_action js_drop" wire:click='onShowModal({{$paroissien->id}})'><i class="fa fa-lock" ></i></span>
                                    @endcan --}}
                                    @can('paroissiens.update')
                                        <a href="{{route('paroissiens.update', $paroissien->id)}}" class="update btn_action js_drop">
                                            <i class="fa fa-pencil" ></i>
                                        </a>
                                    @endcan
                                    @can('paroissiens.delete')
                                        <span class="update btn_action" wire:click='onShowDeleteModal({{$paroissien->id}})'> <i class="fa fa-trash"></i> </span>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $paroissiens->links() }}
        </div>
    </div>


    <div class="modal_wrapper {{$showModal ? 'show' : ''}}" >
        <div class="card modal">
            <div class="modal_header">
                <h1>Désactivation d'un paroissien</h1>
                <i class="fa fa-plus js_cross"  wire:click='onShowModal'></i>
            </div>
            <div class="modal_body">
                <form action="" wire:submit.prevent='onSubmit'>
                    <p>Souhaitez-vous vraiment désactiver le paroissien <span>{{$paroi_name}}</span>?</p>

                    <div class="btn_block add_block d_flex ai_center jc_center w_100">
                        <button class="btn btn_main btn_outline js_abort" type="button"  wire:click='onShowModal'>
                            <span>Annuler</span>
                        </button>
                        <button  class="btn" type="submit">
                            <span>Confirmer</span>
                            <span wire:loading><i class="fa fa-spin fa-spinner"></i></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal_wrapper modal1 {{ $showDeleteModal ? 'show' : '' }}">
        <div class="card modal">
            <div class="modal_header">
                <h1>Suppression d'un paroissien</h1>
                <i class="fa fa-plus js_cross" wire:click='onShowDeleteModal'></i>
            </div>
            <div class="modal_body">
                <p>Souhaitez-vous vraiment supprimer ce paroissien?</p>

                <div class="btn_block add_block d_flex ai_center jc_center w_100">
                    <button class="btn btn_main btn_outline js_abort" wire:click='onShowDeleteModal'>
                        <span>Annuler</span>
                    </button>
                    <button class="btn" wire:click='onDelete' wire:loading.attr='disabled'>
                        <span>Confirmer</span>
                        <span wire:loading wire:target='onDelete'><i class="fa fa-spin fa-spinner"></i></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal_wrapper modal1 {{ $showImportModal ? 'show' : '' }}">
        <div class="card modal">
            <div class="modal_header">
                <h1>Importer des paroissiens</h1>
                <i class="fa fa-plus js_cross" wire:click='onShowImportModal'></i>
            </div>
            <div class="modal_body" wire:ignore.self>
                <h3>Info</h3>
                <p>Pour importer utilisez un fichier csv separer par des <b>;</b>. le fichier doit avoir un tableau avec une ligne d'entete possedant les valeurs suivante : </p>
                <ol style="column-count: 3; margin-bottom: 1.5rem; font-weight:bold;padding: 0 1rem;">
                    <li>firstname</li>
                    <li>lastname</li>
                    <li>situation</li>
                    <li>job</li>
                    <li>service_place</li>
                    <li>job_poste</li>
                    <li>baptise_date</li>
                    <li>adhesion_date</li>
                    <li>confirm_date</li>
                    <li>new_matricule</li>
                    <li>genre</li>
                    <li>birthdate</li>
                    <li>birthplace</li>
                    <li>email</li>
                    <li>school_level</li>
                    <li>address</li>
                    <li>phone</li>
                    <li>categorie</li>
                    <li>father_name</li>
                    <li>mother_name</li>
                    <li>wife_or_husban_name</li>
                    <li>marital_status</li>
                    <li>nb_children</li>
                    <li>old_matricule</li>
                    <li>association</li>
                </ol>
                <form  action="{{route('import.all')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="model" value="paroissiens">
                    <div class="form_group">
                        <label for="file">Fichier</label>
                        <div class="form_input">
                            <input type="file" name="file"  accept=".csv" required id="file">
                        </div>
                        @error('file')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="btn_block add_block d_flex ai_center jc_center w_100">
                        <button type="button" class="btn btn_main btn_outline js_abort">
                            <span>Annuler</span>
                        </button>
                        <button class="btn" type="submit">
                            <span>Importer</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



