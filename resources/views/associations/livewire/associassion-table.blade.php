<div>
    <div class="body_header d_flex jc_between ai_center">
        <h1 class="body_header_title">Associations</h1>

        <div class="btn_block d_flex ai_center">
            @can("association.print")
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
                      <x-table-th :direction="$orderDirection" label="Association" name="name" :field="$orderField">Association</x-table-th>
                      <x-table-th :direction="$orderDirection" label="Sigle" name="sigle" :field="$orderField">Sigle</x-table-th>
                      <th>Cumul des offrandes</th>
                      <x-table-th :direction="$orderDirection" label="Créer le" name="created_at" :field="$orderField">Sigle</x-table-th>
                      <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                        @foreach ($associations as $asso)
                            <tr>
                            <td>
                                <span>{{$asso->id}}</span>
                            </td>
                            <td>
                                <span>{{$asso->name}}</span>
                            </td>
                            <td><span>{{$asso->sigle}}</span></td>
                            <td><span>{{($asso->std_min)}}</span></td>
                            <td><span>{{$asso->created_at}}</span></td>
                            <td>
                                <div class="t_action">
                                    @can("association.offrande.index")
                                        <a href = "{{route('association.offrande.index', $asso->id)}}" class="update btn_action"><i class="fa fa-eye"></i></a>
                                    @endcan
                                    @can("association.update")
                                        <span class="disabled btn_action" wire:click='onShowModal({{$asso->id}})'><i class="fa fa-pencil"></i></span>
                                    @endcan
                                    @can("association.delete")
                                        <span class="disabled btn_action" wire:click='onSHowDeleteModal({{$asso->id}})' ><i class="fa fa-trash"></i></span>
                                    @endcan
                                </div>
                            </td>
                            </tr>
                        @endforeach
                </tbody>
            </table>
            {{ $associations->links() }}

        </div>
    </div>

    <div class="modal_wrapper modal1 {{ $showDeleteModal ? 'show' : '' }}">
        <div class="card modal">
            <div class="modal_header">
                <h1>Suppression d'une association</h1>
                <i class="fa fa-plus js_cross" wire:click='onSHowDeleteModal'></i>
            </div>
            <div class="modal_body">
                <p>Souhaitez-vous vraiment supprimer cette association?</p>

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
                <h1>{{$id ? "Mise à jour de l'association" : "Nouvelle association"}}</h1>
                <i class="fa fa-plus js_cross" wire:click='onShowModal'></i>
            </div>
            <div class="modal_body">
                <form action="" wire:submit.prevent='onSubmit'>
                    <div class="form_group">
                        <label for="association">Nom de l'association</label>
                        <div class="form_input">
                            <input type="text" name="association" wire:model='name' required id="association" placeholder="Entrer le nom de l'association">
                        </div>
                        @error('name') <span class="error">{{ $message }}</span> @enderror
                    </div>
                    <div class="form_group">
                        <label for="sigle">Sigle de l'association</label>
                        <div class="form_input">
                            <input type="text" name="sigle" wire:model='sigle' required id="sigle" placeholder="Entrer le sigle de l'association">
                        </div>
                        @error('sigle') <span class="error">{{ $message }}</span> @enderror
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

    <div class="modal_wrapper modal1 {{ $showImportModal ? 'show' : '' }}">
        <div class="card modal">
            <div class="modal_header">
                <h1>Importer des associations</h1>
                <i class="fa fa-plus js_cross" wire:click='onShowImportModal'></i>
            </div>
            <div class="modal_body" wire:ignore.self>
                <h3>Info</h3>
                <p>Pour importer utilisez un fichier csv separer par des <b>;</b>. le fichier doit avoir un tableau avec une ligne d'entete possedant les valeurs suivante : </p>
                <ol style="column-count: 3; margin-bottom: 1.5rem; font-weight:bold;padding: 0 1rem;">
                    <li>nom</li>
                </ol>
                <form  action="{{route('import.all')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="model" value="associations">
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
