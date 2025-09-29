<div>
    <div class="body_header d_flex jc_between ai_center flex_wrap_md">
        <h1 class="body_header_title">Engagements</h1>

        <div class="btn_block d_flex ai_center flex_wrap_sm">
            @can("engagement.print")
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
            @can("engagement.create")
                <a href = "{{route('engagement.create')}}" class="btn">
                    <i class="fa fa-plus"></i>
                    <span>Ajouter</span>
                </a>
            @endcan
            @if (!$isCurrentYear)
                @can("engagement.migrate")
                    <span class="btn" wire:click='onShowMigrateModal()'>
                        <i class="fa-solid fa-money-bill-transfer"></i>
                        <span>Migrate</span>
                    </span>
                @endcan
            @endif
        </div>
    </div>
    <div class="stat_cards flex_wrap_1250">
        <div class="stat">
            <h4>Dime {{session('year')}}</h4>
            <h2>{{formatNumber($statsData->somme_dime ?? 0)}} FCFA</h2>
        </div>
        <div class="stat">
            <h4>Construction {{session('year')}}</h4>
            <h2>{{formatNumber($statsData->somme_construction ?? 0)}} FCFA</h2>
        </div>
        <div class="stat red">
            <h4>Dette dime {{session('year') - 1}}</h4>
            <h2>{{formatNumber($statsData->somme_dette_dime ?? 0)}} FCFA</h2>
        </div>
        <div class="stat red">
            <h4>Dette construction {{session('year') - 1}}</h4>
            <h2>{{formatNumber($statsData->somme_dette_cotisation ?? 0)}} FCFA</h2>
        </div>
    </div>
    <div class="card">
        @livewire('Alert')
        <div class="card_header filters">
            <div class="form_group form_search">
                <div class="form_input" >
                    <input  type="text" name="find" id="find" placeholder="Rechercher..." wire:model.live.debounce.500ms="term">
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
                    <th>Ancien Matricule</th>
                    <x-table-th :direction="$orderDirection" label="Paroissien" name="paroissiens_id" :field="$orderField">Paroissien</x-table-th>
                    <x-table-th cls="d-sm" :direction="$orderDirection" label="Situation" name="situation" :field="$orderField">Situation</x-table-th>
                    <x-table-th cls="d-sm" :direction="$orderDirection" label="Association" name="associations_id" :field="$orderField">Association</x-table-th>
                    <th>Dime / Construction</th>
                    <x-table-th :direction="$orderDirection" label="Dette (dime / construction)" name="dette" :field="$orderField">Dette (versé / total)</x-table-th>
                    <th>Total versé / Total restant</th>
                    {{-- <x-table-th :direction="$orderDirection" label="Recolte" name="offrande" :field="$orderField">Recolte</x-table-th> --}}
                    <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($engagements as $engagement)
                        <tr>
                            <td>
                                <span>{{$engagement->paroissien->old_matricule}}</span>
                            </td>
                            <td><span>{{$engagement->paroissien->firstname}} {{$engagement->paroissien->lastname}}</span></td>
                            <td><span>{{$engagement->paroissien->situation}}</span></td>
                            <td><span>{{$engagement->association->name}}</span></td>
                            <td><span>{{formatNumber($engagement->dime)}} / {{formatNumber($engagement->cotisation)}} Fcfa</span></td>
                            <td><span>{{formatNumber($engagement->dette_dime)}} / {{formatNumber($engagement->dette_cotisation)}} Fcfa</span></td>
                            <td><span>{{formatNumber($engagement->avg_versement['solde'])}} / {{formatNumber($engagement->avg_versement['reste'])}} Fcfa</span></td>
                            {{-- <td><span>{{$engagement->offrande}}</span></td> --}}
                            <td>
                                <div class="t_action">
                                    @can("engagement.update")
                                        <a href="{{route('engagement.update', $engagement->id)}}" class="update btn_action" > <i class="fa fa-pencil"></i> </a>
                                    @endcan
                                    @can("engagement.delete")
                                    <span class="disabled btn_action" wire:click='onShowDeleteModal({{ $engagement->id }})'><i class="fa fa-trash"></i></span>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{ $engagements->links() }}
        </div>
    </div>


    <div class="modal_wrapper modal1 {{ $showDeleteModal ? 'show' : '' }}">
        <div class="card modal">
            <div class="modal_header">
                <h1>Suppression d'un engagement</h1>
                <i class="fa fa-plus js_cross" wire:click='onShowDeleteModal'></i>
            </div>
            <div class="modal_body">
                <p>Souhaitez-vous vraiment supprimer ce engagement?</p>

                <div class="btn_block add_block d_flex ai_center jc_center w_100">
                    <button class="btn btn_main btn_outline js_abort" wire:click='onShowDeleteModal'>
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

    <div class="modal_wrapper modal1 {{ $showMigrateModal ? 'show' : '' }}">
        <div class="card modal">
            <div class="modal_header">
                <h1>Migrer les engagements</h1>
                <i class="fa fa-plus js_cross" wire:click='onShowMigrateModal'></i>
            </div>
            <div class="modal_body">
                <p>Souhaitez-vous vraiment migrer ces engagement?</p>

                <div class="btn_block add_block d_flex ai_center jc_center w_100">
                    <button class="btn btn_main btn_outline js_abort" wire:click='onShowMigrateModal'>
                        <span>Annuler</span>
                    </button>
                    <a class="btn" href="{{route('engagement.migrate')}}">
                        <span>Confirmer</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="modal_wrapper modal1 {{ $showImportModal ? 'show' : '' }}">
        <div class="card modal">
            <div class="modal_header">
                <h1>Importer des engagements</h1>
                <i class="fa fa-plus js_cross" wire:click='onShowImportModal'></i>
            </div>
            <div class="modal_body" wire:ignore.self>
                <h3>Info</h3>
                <p>Pour importer utilisez un fichier csv separer par des <b>;</b>. le fichier doit avoir un tableau avec une ligne d'entete possedant les valeurs suivante : </p>
                <ol style="column-count: 2; margin-bottom: 1.5rem; font-weight:bold;padding: 0 1rem;">
                    <li>matricule_paroissien</li>
                    <li>annee_engagement</li>
                    <li>dime</li>
                    <li>construction</li>
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
