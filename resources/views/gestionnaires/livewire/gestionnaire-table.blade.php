<div>
    <div class="body_header d_flex jc_between ai_center">
        <h1 class="body_header_title">Gestionnaires</h1>

        <div class="btn_block d_flex ai_center">
            <label class="btn btn_main btn_outline" wire:click='onShowImportModal()'>
                <i class="fa fa-file-export"></i>
                <span>Importer</span>
            </label>
            @can('gestionnaire.print')
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
            @can('gestionnaire.create')
                <button class="btn js_new" wire:click='onShowModal'>
                    <i class="fa fa-plus"></i>
                    <span>Ajouter</span>
                </button>
            @endcan
        </div>
    </div>
    <div class="card">
        <div class="card_header filters">
            <div class="form_group form_search">
                <div class="form_input">
                    <input type="text" placeholder="Rechercher..." wire:model.live.debounce.500ms="term">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </div>
            </div>
        </div>
        <div class="card_content">
            @livewire('Alert')
            <table>
                <thead>
                    <tr>
                        <x-table-th :direction="$orderDirection" label="Nom(s) & Prénom(s)" name="name" :field="$orderField">Nom(s)
                            & Prénom(s)</x-table-th>
                        <x-table-th :direction="$orderDirection" label="Association" name="association_id"
                            :field="$orderField">Association</x-table-th>
                        <x-table-th :direction="$orderDirection" label="Adresse" name="address"
                            :field="$orderField">Adresse</x-table-th>
                            <x-table-th :direction="$orderDirection" label="Email" name="email"
                            :field="$orderField">Email</x-table-th>
                        <x-table-th :direction="$orderDirection" label="Contact" name="phone"
                            :field="$orderField">Contact</x-table-th>
                        <x-table-th :direction="$orderDirection" label="Role" name="role_id" :field="$orderField">Role</x-table-th>
                        <x-table-th :direction="$orderDirection" label="Fonction" name="statut" :field="$orderField">Statut
                            Paroissial</x-table-th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($gestionnaires as $gestionnaire)
                        <tr>
                            <td>
                                <span>{{ $gestionnaire->name }}</span>
                            </td>
                            <td>
                                <span>{{ $gestionnaire->associations->name }}</span>
                            </td>
                            <td><span>{{ $gestionnaire->address }}</span></td>
                            <td><span>{{ $gestionnaire->user->email }}</span></td>
                            <td><span>{{ $gestionnaire->phone }}</span></td>
                            <td><span>{{ $gestionnaire->roles->name }}</span></td>
                            <td><span>{{ $gestionnaire->statut }}</span></td>
                            <td>
                                <div class="t_action">
                                    @can('gestionnaire.update')
                                        <span class="disabled btn_action"
                                            wire:click='onShowModal({{ $gestionnaire->id }})'><i
                                                class="fa fa-pencil"></i></span>
                                    @endcan
                                    @can('gestionnaire.delete')
                                        <span class="disabled btn_action"
                                            wire:click='onSHowDeleteModal({{ $gestionnaire->id }})'><i
                                                class="fa fa-trash"></i></span>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $gestionnaires->links() }}
        </div>
    </div>
    <div class="modal_wrapper modal1 {{ $showDeleteModal ? 'show' : '' }}">
        <div class="card modal">
            <div class="modal_header">
                <h1>Suppression d'un gestionnaire</h1>
                <i class="fa fa-plus js_cross" wire:click='onSHowDeleteModal'></i>
            </div>
            <div class="modal_body">
                <p>Souhaitez-vous vraiment supprimer ce gestionnaire?</p>

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
    <div class="modal_wrapper modal2 {{ $showModal ? 'show' : '' }}">
        <div class="card modal">
            <div class="modal_header">
                <h1>{{ $id ? 'Mise à jour du gestionnaire' : 'Nouveau gestionnaire' }}</h1>
                <i class="fa fa-plus js_cross" wire:click='onShowModal'></i>
            </div>
            <div class="modal_body">
                <form action="" method="post" wire:submit.prevent='onSubmit'>
                    <div class="form_flex">
                        <div class="form_group">
                            <label for="name">Nom et prenom</label>
                            <div class="form_input">
                                <input type="text" name="name" wire:model='gestionnaireForm.name' required
                                    id="name" placeholder="Nom et prenom">
                                @error('gestionnaireForm.name')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form_group">
                            <label for="association">Association concerné</label>
                            <div class="form_input">
                                <select name="association" wire:model='gestionnaireForm.association_id' required
                                    id="association">
                                    <option value="" selected>Association</option>
                                    @foreach ($associations as $asso)
                                        <option value="{{ $asso->id }}">{{ $asso->name }}</option>
                                    @endforeach
                                </select>
                                @error('gestionnaireForm.association_id')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form_flex">
                        <div class="form_group">
                            <label for="adress">Address</label>
                            <div class="form_input">
                                <input type="text" name="adress" wire:model='gestionnaireForm.address' required
                                    id="adress" placeholder="Address">
                            </div>
                            @error('gestionnaireForm.address')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form_group">
                            <label for="phone">Numero de téléphone</label>
                            <div class="form_input">
                                <input type="tel" name="phone" wire:model='gestionnaireForm.phone' required
                                    id="phone" placeholder="Numero de téléphone">
                            </div>
                            @error('gestionnaireForm.phone')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form_flex">
                        <div class="form_group">
                            <label for="fonction">Role</label>
                            <div class="form_input">
                                <select name="role" wire:model='gestionnaireForm.role_id' id="fonction">
                                    <option value="" selected>Role</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('gestionnaireForm.role_id')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form_group">
                            <label for="status">Statut Paroissial</label>
                            <div class="form_input">
                                <select name="status" wire:model='gestionnaireForm.statut' id="status">
                                    <option value="" selected>Categorie</option>
                                    <option value="Pasteur" >Pasteur</option>
                                    <option value="Ancien" >Ancien</option>
                                    <option value="Diacre" >Diacre</option>
                                    <option value="Fidele" >Fidèle</option>
                                </select>
                            </div>
                            @error('gestionnaireForm.statut')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    @if ($id == null)
                        <div class="form_group">
                            <label for="email">Email</label>
                            <div class="form_input">
                                <input type="email" name="email" wire:model='gestionnaireForm.email'
                                    id="email" placeholder="Entrer l'email">
                            </div>
                            @error('gestionnaireForm.email')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                    @endif

                    <div class="btn_block add_block d_flex ai_center jc_center w_100">
                        <button type="button" class="btn btn_main btn_outline js_abort" wire:click='onShowModal'>
                            <span>Annuler</span>
                        </button>
                        <button class="btn" type="submit" wire:loading.attr='disabled'>
                            <span>{{ $id ? 'Modifier' : 'Ajouter' }}</span>
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
                <h1>Importer des gestionnaires</h1>
                <i class="fa fa-plus js_cross" wire:click='onShowImportModal'></i>
            </div>
            <div class="modal_body" wire:ignore.self>
                <h3>Info</h3>
                <p>Pour importer utilisez un fichier csv separer par des <b>;</b>. le fichier doit avoir un tableau avec une ligne d'entete possedant les valeurs suivante : </p>
                <ol style="column-count: 2; margin-bottom: 1.5rem; font-weight:bold;padding: 0 1rem;">
                    <li>telephone</li>
                    <li>email</li>
                    <li>nom</li>
                    <li>prenom</li>
                    <li>status</li>
                    <li>address</li>
                    <li>role</li>
                    <li>association</li>
                </ol>
                <form  action="{{route('import.all')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="model" value="gestionnaires">
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
