<div class="card stat-2 max">
    <div class="header">
        <h5>
            Taux global d'achevement des engagements par association ({{$type}})
            <span wire:loading wire:target="getAssociationPaiementStat"><i class="fa fa-spin fa-spinner"></i></span>
        </h5>
        <div class="form_group">
            <div class="form_input">
                <select name="categorie" id="categorie" wire:model.live='type' wire:change='getAssociationPaiementStat'>
                    <option value="dime" >Dime</option>
                    <option value="dette_dime" >Dette dime</option>
                    <option value="dette_cotisation" >Dette cotisation</option>
                    {{-- <option value="Offrande de recolte" >Offrande de recolte</option> --}}
                    <option value="Offrande de construction" >Offrande de construction</option>
                </select>
            </div>
        </div>
    </div>
    <p wire:loading wire:target="getAssociationPaiementStat">
        Chargement..
        <span ><i class="fa fa-spin fa-spinner"></i></span>
    </p>
    <div class="stat_block" wire:loading.remove>
        @foreach ($assoStat as $asso)
                <div class="asso-stat">
                    <div class="label">{{$asso['sigle']}}</div>
                    <div class="percent-bar-wrapper">
                        <div class="percent-value"
                        style="--stat-width: {{$asso['percent']}}%">
                        </div>
                        <div class="percent-value-text"
                        style="--negative-stat-width: -{{($asso['percent'] == 100 || $asso['percent'] == 0) ? 50 : $asso['percent']}}%"
                        >{{$asso['percent']}} % ({{formatNumber($asso['data']['recu'])}} FCFA)</div>
                    </div>
                </div>
        @endforeach
    </div>
</div>
