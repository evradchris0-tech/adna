<div>
    <div class="body_header d_flex jc_between ai_center">
        <h1 class="body_header_title">{{!$id ? "Ajouter une cotisation" : "Modifier une cotisation"}}</h1>
    </div>
    <div class="card">
        @if (session("error") || session("message"))
            @livewire('Alert')
        @endif
        <div class="card_content">
            <form action="" wire:submit.prevent='onSubmit'>
                <div class="form_flex">
                    @include('components.paroissien-select',
                    [
                        $paroissiens,
                        'isRequired' => true,
                        'hasEvent' => 'loadEngagement',
                        'isLive' => true,
                        'label' => 'Paroissien concerné',
                    ])
                </div>
                <div class="form_flex">
                    <div class="form_group required">
                        <label for="type">Type</label>
                        <div class="form_input">
                            <select name="type" id="type" wire:model='cotisationForm.type'>
                                <option value="" selected>Type</option>
                                <option value="recolte" selected>Recolte</option>
                                <option value="autres recettes" >Autres recettes</option>
                            </select>
                        </div>
                        @error('cotisationForm.type') <span class="error">{{ $message }}</span> @enderror
                    </div>
                    <div class="form_group required">
                        <label for="amount">Somme</label>
                        <div class="form_input">
                            <input type="number" name="amount" wire:model='cotisationForm.somme' required id="amount" placeholder="Somme">
                        </div>
                        @error('cotisationForm.somme') <span class="error">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="btn_block add_block d_flex ai_center jc_center w_100">
                    <a href="/versements" class="btn btn_main btn_outline">
                        <span>Annuler</span>
                    </a>
                    @if ($id)
                        <button type="button" class="btn" wire:click='onShowConfirmModal'>
                            <span>Modifier</span>
                        </button>
                    @else
                        <button type="submit" class="btn">
                            <span>Enregistrer</span>
                            <span wire:loading wire:target="onSubmit"><i class="fa fa-spin fa-spinner"></i></span>
                        </button>
                    @endif
                </div>

            </form>
        </div>
    </div>

    <div class="modal_wrapper modal1 {{ $showConfirm ? 'show' : '' }}">
        <div class="card modal">
            <div class="modal_header">
                <h1>Confirmation</h1>
                <i class="fa fa-plus js_cross" wire:click='onShowConfirmModal'></i>
            </div>
            <div class="modal_body">
                <p>Souhaitez-vous modifier cette information?</p>

                <div class="btn_block add_block d_flex ai_center jc_center w_100">
                    <button class="btn btn_main btn_outline js_abort" wire:click='onShowConfirmModal'>
                        <span>Annuler</span>
                    </button>
                    <button class="btn" wire:click='onSubmit' wire:loading.attr='disabled'>
                        <span>Confirmer</span>
                        <span wire:loading wire:target='onSubmit'><i class="fa fa-spin fa-spinner"></i></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
