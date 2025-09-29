<div>
    <div class="body_header d_flex jc_between ai_center">
        <h1 class="body_header_title">Liste des Roles</h1>

        <div class="btn_block d_flex ai_center">
            @can('roles.create')
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
                        <x-table-th :direction="$orderDirection" label="N°" name="id" :field="$orderField">N°</x-table-th>
                        <x-table-th :direction="$orderDirection" label="Nom" name="name" :field="$orderField">Nom</x-table-th>
                        <x-table-th :direction="$orderDirection" label="Date creation" name="created_at" :field="$orderField">Date
                            creation</x-table-th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($roles as $role)
                        <tr>
                            <td>
                                <span>{{ $role->id }}</span>
                            </td>
                            <td>
                                <span>{{ $role->name }}</span>
                            </td>
                            <td><span>{{ $role->created_at }}</span></td>
                            <td>
                                <div class="t_action">
                                    @if ($role->name != "admin")
                                        @can('roles.update')
                                            <span class="disabled btn_action" wire:click='onShowModal({{ $role->id }})'><i
                                                    class="fa fa-pencil"></i></span>
                                        @endcan
                                        @can('roles.delete')
                                            <span class="disabled btn_action" wire:click='onShowDeleteModal({{ $role->id }})'><i class="fa fa-trash"></i></span>
                                        @endcan
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $roles->links() }}
        </div>
    </div>

    <div class="modal_wrapper {{ $showModal ? 'show' : '' }}">
        <div class="card modal">
            <div class="modal_header">
                <h1>{{ $id ? 'Mise à jour du role' : 'Nouveau role' }}</h1>
                <i class="fa fa-plus js_cross" wire:click='onShowModal'></i>
            </div>
            <div class="modal_body">
                <form action="" wire:submit.prevent='onSubmit'>
                    <div class="form_group">
                        <label for="name">Nom du role</label>
                        <div class="form_input">
                            <input type="text" name="name" wire:model='name' required id="name"
                                placeholder="Entrer le nom du role">
                        </div>
                        @error('name')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form_group">
                        <label for="permissions">Liste des permissions</label>
                        @livewire('multi-select-component', ['datas' => $permissions, 'selected' => $droppedPermissions])
                        @error('permissions')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
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

    <div class="modal_wrapper modal1 {{ $showDeleteModal ? 'show' : '' }}">
        <div class="card modal">
            <div class="modal_header">
                <h1>Suppression d'un role</h1>
                <i class="fa fa-plus js_cross" wire:click='onShowDeleteModal'></i>
            </div>
            <div class="modal_body">
                <p>Souhaitez-vous vraiment supprimer ce role?</p>

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



</div>
@section('js')
    <script type="text/javascript">
        $(document).ready(function() {
            Livewire.on('dataSelected', data => {
                @this.set('droppedPermissions', data);
                @this.dispatch('sendData');
            })
        });
    </script>
@endsection
