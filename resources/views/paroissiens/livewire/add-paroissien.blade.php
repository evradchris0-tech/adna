<div>
    <div class="menu_tab">
        <ul>
            <li @class(["active" => $current == 0, "completed" => $current == 1 || $current == 2]) >
                <span>
                    <b>1</b>
                    <i class="fa fa-check"></i>
                </span>
                <p>Infos personnelles</p>
            </li>
            <li @class(["active" => $current == 1 , "completed" => $current == 2])>
                <span>
                    <b>2</b>
                    <i class="fa fa-check"></i>
                </span>
                <p>Infos paroisse</p>
            </li>
            <li @class(["active" => $current == 2])>
                <span>
                    <b>3</b>
                    <i class="fa fa-check"></i>
                </span>
                <p>Infos Suplémentaires</p>
            </li>
            <!-- <li class="bar"></li> -->
        </ul>
    </div>
    @livewire('Alert')
    <form action="" wire:submit.prevent='onSubmit'>
        <div @class(["step step_1", "show_step" => $current == 0])>
            <div class="form_flex">
                <div class="form_group required">
                    <label for="nom">Nom(s)</label>
                    <div class="form_input has_icon_block_left">
                        <input type="text" name="nom" wire:model='paroissien.firstname'  id="nom" placeholder="Nom">
                    </div>
                    @error('paroissien.firstname') <span class="error">{{ $message }}</span> @enderror
                </div>
                <div class="form_group required">
                    <label for="prenom">Prénom(s)</label>
                    <div class="form_input has_icon_block_left">
                        <input type="text" name="prenom" wire:model='paroissien.lastname'  id="prenom" placeholder="Prénom">
                    </div>
                    @error('paroissien.lastname') <span class="error">{{ $message }}</span> @enderror
                </div>
                <div class="form_group required">
                    <label for="paroissiens">Genre</label>
                    <div class="form_input">
                        <select name="genre" id="paroissiens" wire:model='paroissien.genre'>
                            <option value="" selected>Sexe</option>
                            <option value="h" >Homme</option>
                            <option value="f">Femme</option>
                        </select>
                    </div>
                    @error('paroissien.genre') <span class="error">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="form_flex">
                <div class="form_group required">
                    <label for="birthdate">Date de naissance</label>
                    <div class="form_input has_icon_block_left">
                        <input type="date" name="birthdate"  id="birthdate" wire:model='paroissien.birthdate'>
                    </div>
                    @error('paroissien.birthdate') <span class="error">{{ $message }}</span> @enderror
                </div>
                <div class="form_group required">
                    <label for="birthplace">Lieu de naissance</label>
                    <div class="form_input has_icon_block_left">
                        <input type="text" name="birthplace"  id="birthplace" placeholder="Lieu de naissance" wire:model='paroissien.birthplace'>
                    </div>
                    @error('paroissien.birthplace') <span class="error">{{ $message }}</span> @enderror
                </div>
                <div class="form_group required">
                    <label for="paroissiens">Niveau d'étude</label>
                    <div class="form_input">
                        <select name="paroissiens" id="paroissiens" wire:model='paroissien.school_level'>
                            <option value="" selected>Niveau d'etude</option>
                            <option value="Cep" >Cep</option>
                            <option value="Bepc" >Bepc</option>
                            <option value="Probatoire" >Probatoire</option>
                            <option value="Baccalaureat" >Baccalaureat</option>
                            <option value="DUT" >DUT</option>
                            <option value="BTS" >BTS</option>
                            <option value="Licence" >Licence</option>
                            <option value="Master / Ingénieur" >Master / Ingénieur</option>
                            <option value="Doctorat / Phd" >Doctorat / Phd</option>
                        </select>
                    </div>
                    @error('paroissien.school_level') <span class="error">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="form_flex">
                <div class="form_group">
                    <label for="email">Email</label>
                    <div class="form_input has_icon_block_left">
                        <input type="email" name="email"  id="email" placeholder="Email" wire:model='paroissien.email'>
                    </div>
                    @error('paroissien.email') <span class="error">{{ $message }}</span> @enderror
                </div>
                <div class="form_group required">
                    <label for="address">Adresse</label>
                    <div class="form_input has_icon_block_left">
                        <input type="text" name="address"  id="address" placeholder="Adresse" wire:model='paroissien.address'>
                    </div>
                    @error('paroissien.address') <span class="error">{{ $message }}</span> @enderror
                </div>
                <div class="form_group required">
                    <label for="phone">Numéro téléphone</label>
                    <div class="form_input has_icon_block_left">
                        <input type="number" name="phone"  id="phone" placeholder="Numéro téléphone" wire:model='paroissien.phone'>
                    </div>
                    @error('paroissien.phone') <span class="error">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>
        <div @class(["step step_2", "show_step" => $current == 1])>
            <div class="form_flex">
                <div class="form_group required">
                    <label for="old_matricule">Ancien matricule</label>
                    <div class="form_input has_icon_block_left">
                        <input type="text" name="old_matricule"  id="old_matricule" wire:model='paroissien.old_matricule' placeholder="Ancien matricule">
                    </div>
                    @error('paroissien.old_matricule') <span class="error">{{ $message }}</span> @enderror
                </div>
                <div class="form_group required">
                    <label for="association">Association</label>
                    <div class="form_input">
                        <select name="association" id="association" wire:model='paroissien.association_id'>
                            <option value="" selected>Association</option>
                            @foreach ($associations as $asso)
                                <option value="{{$asso->id}}">{{$asso->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('paroissien.association_id') <span class="error">{{ $message }}</span> @enderror
                </div>
                <div class="form_group required">
                    <label for="categorie">Catégorie</label>
                    <div class="form_input">
                        <select name="categorie" id="categorie" wire:model='paroissien.categorie'>
                            <option value="" selected>Categorie</option>
                            <option value="Pasteur" >Pasteur</option>
                            <option value="Ancien" >Ancien</option>
                            <option value="Diacre" >Diacre</option>
                            <option value="Fidele" >Fidèle</option>
                        </select>
                    </div>
                    @error('paroissien.categorie') <span class="error">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="form_flex">
                <div class="form_group">
                    <label for="baptemeDate">Date de bapteme</label>
                    <div class="form_input has_icon_block_left">
                        <input type="date" name="baptemeDate"  id="baptemeDate" wire:model='paroissien.baptise_date'>
                    </div>
                    @error('paroissien.baptise_date') <span class="error">{{ $message }}</span> @enderror
                </div>
                <div class="form_group">
                    <label for="confirmdate">Date de confirmation</label>
                    <div class="form_input has_icon_block_left">
                        <input type="date" name="confirmdate"  id="confirmdate" wire:model='paroissien.confirm_date'>
                    </div>
                    @error('paroissien.confirm_date') <span class="error">{{ $message }}</span> @enderror
                </div>
                <div class="form_group">
                    <label for="adhdate">Date d'adhésion</label>
                    <div class="form_input has_icon_block_left">
                        <input type="date" name="adhdate"  id="adhdate" wire:model='paroissien.adhesion_date'>
                    </div>
                    @error('paroissien.adhesion_date') <span class="error">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>
        <div @class(["step step_3", "show_step" => $current == 2])>
            <div class="form_flex">
                <div class="form_group required">
                    <label for="father_name">Nom père</label>
                    <div class="form_input has_icon_block_left">
                        <input type="text" name="father_name"  id="father_name"  wire:model='paroissien.father_name' placeholder="Nom père">
                    </div>
                    @error('paroissien.father_name') <span class="error">{{ $message }}</span> @enderror
                </div>
                <div class="form_group required">
                    <label for="mother_name">Nom mère</label>
                    <div class="form_input has_icon_block_left">
                        <input type="text" name="mother_name"  id="mother_name"  wire:model='paroissien.mother_name' placeholder="Nom mère">
                    </div>
                    @error('paroissien.mother_name') <span class="error">{{ $message }}</span> @enderror
                </div>
                <div class="form_group">
                    <label for="epoux">Epoux(se)</label>
                    <div class="form_input has_icon_block_left">
                        <input type="text" name="epoux"  id="epoux"  wire:model='paroissien.wife_or_husban_name' placeholder="Epoux(se)">
                    </div>
                    @error('paroissien.wife_or_husban_name') <span class="error">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="form_flex">
                <div class="form_group required">
                    <label for="marital_status">Statut Matrimonial</label>
                    <div class="form_input">
                        <select name="marital_status" id="marital_status" wire:model='paroissien.marital_status'>
                            <option value="" selected>Statut Matrimonial</option>
                            <option value="m">Marié</option>
                            <option value="c">Celibataire</option>
                            <option value="v">Veuf(ve)</option>
                        </select>
                    </div>
                    @error('paroissien.marital_status') <span class="error">{{ $message }}</span> @enderror
                </div>
                <div class="form_group">
                    <label for="nb_children">Nombre enfant</label>
                    <div class="form_input has_icon_block_left">
                        <input type="number" name="nb_children"  id="nb_children" wire:model='paroissien.nb_children'>
                    </div>
                    @error('paroissien.nb_children') <span class="error">{{ $message }}</span> @enderror
                </div>
                <div class="form_group required">
                    <label for="situation">Situation</label>
                    <div class="form_input">
                        <select name="situation" id="situation" wire:model.live='paroissien.situation'>
                            <option value="" selected>Situation</option>
                            <option value="Sans emploi">Sans emploi</option>
                            <option value="Elève / Etudiant">Elève / Etudiant</option>
                            <option value="Employer">Employer</option>
                            <option value="Retraité">Retraité</option>
                            <option value="Invalide">Invalide</option>
                            <option value="Décédé">Décédé</option>
                        </select>
                    </div>
                    @error('paroissien.situation') <span class="error">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="form_flex">
                @if ($paroissien->situation == "Employer")
                    <div class="form_group">
                        <label for="employer">Profession</label>
                        <div class="form_input has_icon_block_left">
                            <input type="text" name="employer"  id="employer" @if($paroissien->situation == "Employer") required @endif wire:model='paroissien.job'  placeholder="Profession">
                        </div>
                        @error('paroissien.job') <span class="error">{{ $message }}</span> @enderror
                    </div>
                    <div class="form_group required">
                        <label for="poste">Poste occupé</label>
                        <div class="form_input has_icon_block_left">
                            <input type="text" name="poste"  id="poste" @if($paroissien->situation == "Employer") required @endif wire:model='paroissien.job_poste'  placeholder="Poste occupé">
                        </div>
                        @error('paroissien.job_poste') <span class="error">{{ $message }}</span> @enderror
                    </div>
                    <div class="form_group">
                        <label for="service_place">Lieu de service</label>
                        <div class="form_input has_icon_block_left">
                            <input type="text" name="service_place" @if($paroissien->situation == "Employer") required @endif  id="service_place" placeholder="Lieu de service" wire:model='paroissien.service_place'>
                        </div>
                        @error('paroissien.service_place') <span class="error">{{ $message }}</span> @enderror
                    </div>
                @endif
            </div>
        </div>
        <div class="btn_block add_block d_flex ai_center jc_center w_100">
            <a href="/paroissiens" class="btn btn_main btn_outline">
                <span>Annuler</span>
            </a>
            <div>
                <button type="button" wire:click='backForward'  @class(["btn btn_main btn_outline btn_prev","is_not_first" => $current == 0])>
                    <span>Précédent</span>
                    <span wire:loading wire:target='backForward'><i class="fa fa-spin fa-spinner"></i></span>
                </button>
                <button type="button" wire:click='goForward'  @class(["btn btn_next","is_not_first" => $current == 2])>
                    <span>Suivant</span>
                    <span wire:loading wire:target='goForward'><i class="fa fa-spin fa-spinner"></i></span>
                </button>
                @if ($id)
                    <button type="button" @class(["btn btn_next","is_not_first" => $current != 2]) wire:click='onShowConfirmModal'>
                        <span>Modifier</span>
                    </button>
                @else
                    <button type="submit" @class(["btn btn_next","is_not_first" => $current != 2])>
                        <span>Enregistrer</span>
                        <span wire:loading wire:target='onSubmit'><i class="fa fa-spin fa-spinner"></i></span>
                    </button>
                @endif
            </div>
        </div>
    </form>


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
