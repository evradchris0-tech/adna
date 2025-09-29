<div>
    <div class="body_header d_flex jc_between ai_center flex_wrap">
        <h1 class="body_header_title">Versements</h1>

        <div class="btn_block d_flex ai_center flex_wrap_sm">
            @can('versement.print')
                <label class="btn btn_main btn_outline" wire:click='onShowImportModal()'>
                    <i class="fa fa-file-export"></i>
                    <span>Importer</span>
                </label>
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

    @include('components.stat', [
        'style' => "stat_1",
        'items' => [
            [
                "class" => "",
                "icon" => "",
                "title" => "Total versé Dime ".session('year'),
                "value" => formatNumber($stats->dime)." FCFA",
            ],
            [
                "class" => "",
                "icon" => "",
                "title" => "Total versé Construction ".session('year'),
                "value" => formatNumber($stats->cotisation) ." FCFA",
            ],
            [
                "class" => "red",
                "icon" => "",
                "title" => "Total versé Dette dime ".(session('year') - 1),
                "value" => formatNumber($stats->dette_dime) ." FCFA",
            ],
            [
                "class" => "red",
                "icon" => "",
                "title" => "Total versé Dette construction ".(session('year') - 1),
                "value" => formatNumber($stats->dette_cotisation) ." FCFA",
            ]
        ],
    ])


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
            <table class="max-content">
                <thead>
                    <tr>
                        <th>Ancien Matricule</th>
                        <x-table-th :direction="$orderDirection" label="Paroissien" name="paroissiens_id" :field="$orderField">Paroissien</x-table-th>
                        <th>Dîme versée / reste Dime</th>
                        <th>Construction Versé / reste Construction</th>
                        <th> Dette Dîme versée / reste Dime</th>
                        <th>Dette Construction Versé / reste Construction</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($paroissiens as $paroissien)
                        @if (count($paroissien->engagements) != 0)
                            <tr>
                                <td>
                                    <span>{{ $paroissien->old_matricule }}</span>
                                </td>
                                <td>
                                    <span>{{ $paroissien->firstname }}{{ $paroissien->lastname }}</span>
                                </td>
                                <td>
                                    <span>
                                        {{formatNumber($paroissien->engagements[0]->available_dime)}} FCFA / {{formatNumber($paroissien->engagements[0]->res_dime)}} FCFA
                                    </span>
                                </td>
                                <td>
                                    <span>
                                        {{formatNumber($paroissien->engagements[0]->available_cotisation)}} FCFA / {{formatNumber($paroissien->engagements[0]->res_cotisation)}} FCFA
                                    </span>
                                </td>
                                <td>
                                    <span>
                                        {{formatNumber($paroissien->engagements[0]->available_dette_dime)}} FCFA / {{formatNumber($paroissien->engagements[0]->res_dette_dime)}} FCFA
                                    </span>
                                </td>
                                <td>
                                    <span>
                                        {{formatNumber($paroissien->engagements[0]->available_dette_cotisation)}} FCFA / {{formatNumber($paroissien->engagements[0]->res_dette_cotisation)}} FCFA
                                    </span>
                                </td>
                                <td>
                                    <div class="t_action">
                                        <a href="{{ route('versement.show', $paroissien->id) }}"
                                            class="update btn_action"> <i class="fa fa-eye"></i> </a>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
            {{ $paroissiens->links() }}
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

    <div class="modal_wrapper modal1 {{ $showImportModal ? 'show' : '' }}">
        <div class="card modal">
            <div class="modal_header">
                <h1>Importer des versements</h1>
                <i class="fa fa-plus js_cross" wire:click='onShowImportModal'></i>
            </div>
            <div class="modal_body" wire:ignore.self>
                <h3>Info</h3>
                <p>Pour importer utilisez un fichier csv separer par des <b>;</b>. le fichier doit avoir un tableau avec une ligne d'entete possedant les valeurs suivante : </p>
                <ol style="column-count: 3; margin-bottom: 1.5rem; font-weight:bold;padding: 0 1rem;">
                    <li>type</li>
                    <li>somme</li>
                    <li>matricule_paroissien</li>
                    <li>engagement_id</li>
                </ol>
                <form  action="{{route('import.all')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="model" value="versements">
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
