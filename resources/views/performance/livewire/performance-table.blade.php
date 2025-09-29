<div>
    <div class="body_header d_flex jc_between ai_center">
        <h1 class="body_header_title">Performance Association</h1>

        <div class="btn_block d_flex ai_center">
            @can('performance.print')
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
        </div>
    </div>
    <div class="card">
        <div class="card_header filters">
            <div class="form_group form_search">
                <div class="form_input" >
                    <input  type="text" placeholder="Rechercher..." wire:model.live.debounce.500ms="term">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <span wire:loading wire:target='term'><i class="fa fa-spin fa-spinner"></i></span>
                </div>
            </div>
        </div>
        <div class="card_header filters" style="justify-content: flex-end">
            <div class="form_group">
                <label for="type">Type</label>
                <div class="form_input">
                    <select name="type" id="type" wire:model="type">
                        <option value="taux" selected>Général</option>
                        <option value="tauxDime" >Dime</option>
                        <option value="tauxDetteDime" >Dette dime</option>
                        <option value="tauxDetteCotisation" >Dette construction</option>
                        <option value="tauxCotisation" >Offrande de construction</option>
                    </select>
                </div>
            </div>
            <div class="form_group">
                <label for="statut">Statut</label>
                <div class="form_input">
                    <select name="statut" id="statut" wire:model="status">
                        <option value="" selected>Statut</option>
                        <option value="1" >Achevé ou Réalisé</option>
                        <option value="2" >En très bonne voie</option>
                        <option value="3" >En bonne voie</option>
                        <option value="4" >Progrès Limités</option>
                        <option value="5" >Progrès très limités</option>
                        <option value="6" >Absence de progrès</option>
                    </select>
                </div>
            </div>
            <button class="btn btn_dark"  wire:click='$refresh' >
                <span>Filtrer</span>
                <span wire:loading wire:target='$refresh'><i class="fa fa-spin fa-spinner"></i></span>
            </button>
        </div>
        <div class="card_content">
            <table class="max-content">
                <thead>
                    <tr>
                        <th>N°</th>
                        <x-table-th :direction="$orderDirection" label="Nom" name="name" :field="$orderField">Nom</x-table-th>
                        <th>Dime</th>
                        <th>Offrande de construction</th>
                        <th>Dette dime (reçu / total)</th>
                        <th>Dette construction (reçu / total)</th>
                        @if ($type == "taux")
                            <th>Statut (Général)</th>
                        @endif
                        @if ($type == "tauxDime")
                            <th>Statut (Dime)</th>
                        @endif
                        @if ($type == "tauxDetteDime")
                            <th>Statut (dette dime)</th>
                        @endif
                        @if ($type == "tauxDetteCotisation")
                            <th>Statut (dette construction)</th>
                        @endif
                        @if ($type == "tauxCotisation")
                            <th>Statut (construction)</th>
                        @endif
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($performances as $perf)
                        <tr>
                            <td><span>{{$perf->id}}</span></td>
                            <td><span>{{$perf->name}}</span></td>
                            <td>
                                <span>{{formatNumber($perf->performance["dimeR"])}} / {{formatNumber($perf->performance["dime"])}} FCFA</span>
                            </td>
                            <td>
                                <span>{{formatNumber($perf->performance["cotisationR"])}} / {{formatNumber($perf->performance["cotisation"])}} FCFA</span>
                            </td>
                            <td><span>{{formatNumber($perf->performance["detteDimeR"])}} / {{formatNumber($perf->performance["detteDime"])}} FCFA</span></td>
                            <td><span>{{formatNumber($perf->performance["detteCotisationR"])}} / {{formatNumber($perf->performance["detteCotisation"])}} FCFA</span></td>
                            <td>
                                <span @class([
                                    'perf',
                                    'good-perf' => $perf->performance[$type] == 100,
                                    'advance-perf' => $perf->performance[$type] < 100 && $perf->performance[$type] >= 75,
                                    'middle-perf' => $perf->performance[$type] < 75 && $perf->performance[$type] >= 50,
                                    'junior-perf' => $perf->performance[$type] < 50 && $perf->performance[$type] >= 25,
                                    'start-perf' => $perf->performance[$type] < 25 && $perf->performance[$type] > 0,
                                    'zero-perf' => $perf->performance[$type] == 0,
                                ])
                                    >{{$perf->performance[$type]}} %</span>
                            </td>
                            <td>
                                <div class="t_action">
                                    <a href="{{ route('performance.show', $perf->id) }}"
                                        class="update btn_action"> <i class="fa fa-eye"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $performances->links() }}
        </div>
    </div>

</div>
