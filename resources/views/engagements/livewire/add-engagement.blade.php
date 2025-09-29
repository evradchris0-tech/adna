<div>
    <div class="body_header d_flex jc_between ai_center">
        <h1 class="body_header_title">{{!$id ? "Ajouter un engagement" : "Modifier un engagement"}}</h1>
    </div>
    <div class="card">
        <div class="card_content">
            @livewire('Alert')
            <form action="" wire:submit.prevent='onSubmit'>
                <div class="form_flex">
                    <div class="form_group required">
                        <label for="periode_start">Debut engagement</label>
                        <div class="form_input has_icon_block_left">
                            <input type="date" name="periode_start" required id="periode_start" wire:model.live='engagementForm.periode_start' wire:change='loadEndDate'>
                        </div>
                        @error('engagementForm.periode_start') <span class="error">{{ $message }}</span> @enderror
                    </div>
                    <div class="form_group">
                        <label for="periode_end">
                            Fin engagement
                            <span wire:loading wire:target="loadEndDate"><i class="fa fa-spin fa-spinner"></i></span>
                        </label>
                        <div class="form_input has_icon_block_left">
                            <input type="date" name="periode_end" disabled required id="periode_end" wire:model='engagementForm.periode_end'>
                        </div>
                        @error('engagementForm.periode_end') <span class="error">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="form_flex">
                    @include(
                        'components.paroissien-select',[
                            $paroissiens,
                            'isRequired' => true,
                            'label' => 'Patient concerné',
                            'hasEvent' => '',
                            'isLive' => false
                    ])
                    <div class="form_group required">
                        <label for="dime">Dîme</label>
                        <div class="form_input has_icon_block_left">
                            <input type="number" name="dime" required id="dime" placeholder="Montant dîme annuel" wire:model='engagementForm.dime'>
                        </div>
                        @error('engagementForm.dime') <span class="error">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="form_group required">
                    <label for="construction">Offrande de construction</label>
                    <div class="form_input has_icon_block_left">
                        <input type="number" name="construction" required id="cotisation" placeholder="Montant construction annuel" wire:model='engagementForm.cotisation'>
                    </div>
                    @error('engagementForm.cotisation') <span class="error">{{ $message }}</span> @enderror
                </div>
                {{-- <div class="form_flex">
                    <div class="form_group required">
                        <label for="offrande">Offrande de récolte</label>
                        <div class="form_input has_icon_block_left">
                            <input type="number" name="offrande" required id="offrande" placeholder="Montant offrande annuel" wire:model='engagementForm.offrande'>
                        </div>
                        @error('engagementForm.offrande') <span class="error">{{ $message }}</span> @enderror
                    </div>
                </div> --}}

                <div class="btn_block add_block d_flex ai_center jc_center w_100">
                    <a href="/engagements" class="btn btn_main btn_outline">
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
