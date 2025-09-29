<div>
    <div class="body_header d_flex jc_between ai_center">
        <h1 class="body_header_title">{{!$id ? "Ajouter un versement" : "Modifier un versement"}}</h1>
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
                    <div class="form_group required">
                        <label for="engagement">
                            Engagement concerné
                            <span wire:loading wire:target="paroissien"><i class="fa fa-spin fa-spinner"></i></span>
                        </label>
                        <div class="form_input">
                            <select name="engagement" id="engagement" required wire:model.live='versementForm.engagement_id' wire:change='loadSommeEngagement'>
                                <option value="" selected>Periode engagement</option>
                                @foreach ($engagements as $engagement)
                                    <option value="{{$engagement->id}}">Du {{$engagement->periode_start}} au {{$engagement->periode_end}}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('versementForm.engagement_id') <span class="error">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="form_flex">
                    <div class="form_group">
                        <label for="engagement">
                            Montant de l'engagement
                            <span wire:loading wire:target="loadSommeEngagement"><i class="fa fa-spin fa-spinner"></i></span>
                        </label>
                        <div class="form_input">
                            <input type="number" disabled name="engagement" id="amount" placeholder="{{formatNumber($montant)}} FCFA" >
                        </div>
                    </div>
                    <div class="form_group">
                        <label for="m_v">
                            Montant deja versé
                            <span wire:loading wire:target="loadSommeEngagement"><i class="fa fa-spin fa-spinner"></i></span>
                        </label>
                        <div class="form_input">
                            <input type="number" disabled name="m_v" id="m_v" placeholder="{{formatNumber($montantVerse)}} FCFA">
                        </div>
                    </div>
                    <div class="form_group">
                        <label for="restant">
                            Montant restant
                            <span wire:loading wire:target="loadSommeEngagement"><i class="fa fa-spin fa-spinner"></i></span>
                        </label>
                        <div class="form_input">
                            <input type="number" disabled name="restant" id="restant" placeholder="{{formatNumber($montant - $montantVerse)}} FCFA">
                        </div>
                    </div>
                    <div class="form_group">
                        <label for="restant_dette_dime">
                            Dette versé / dette total (dime)
                            <span wire:loading wire:target="loadSommeEngagement"><i class="fa fa-spin fa-spinner"></i></span>
                        </label>
                        <div class="form_input">
                            <input type="number" disabled name="restant_dette_dime" id="restant_dette_dime" placeholder="{{formatNumber($dette_dime_verser)}} / {{formatNumber($dette_dime)}} FCFA">
                        </div>
                    </div>
                    <div class="form_group">
                        <label for="restant_dette_cotisation">
                            Dette versé / dette total (construction)
                            <span wire:loading wire:target="loadSommeEngagement"><i class="fa fa-spin fa-spinner"></i></span>
                        </label>
                        <div class="form_input">
                            <input type="number" disabled name="restant_dette_cotisation" id="restant_dette_cotisation" placeholder="{{formatNumber($dette_cotisation_verser)}} / {{formatNumber($dette_cotisation)}} FCFA">
                        </div>
                    </div>

                </div>
                <div class="form_flex">
                    <div class="form_group required">
                        <label for="type">Type</label>
                        <div class="form_input">
                            <select name="type" id="type" required wire:model='versementForm.type'>
                                <option value="" selected>Type</option>
                                <option value="dime" >Dime</option>
                                <option value="dette_dime" >Dette dime</option>
                                <option value="dette_cotisation" >Dette cotisation</option>
                                <option value="Offrande de construction">Offrande de construction</option>
                            </select>
                        </div>
                        @error('versementForm.type') <span class="error">{{ $message }}</span> @enderror
                    </div>
                    <div class="form_group required">
                        <label for="amount">Somme</label>
                        <div class="form_input">
                            <input type="number" name="amount" wire:model='versementForm.somme' required id="amount" placeholder="Somme">
                        </div>
                        @error('versementForm.somme') <span class="error">{{ $message }}</span> @enderror
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
