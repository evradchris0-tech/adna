@extends('layout')
@section('title', 'DETAILS')
@section('css')
    @vite('resources/css/paroisse.scss')
@endsection

@section('body')
    <div class="body_header d_flex jc_between ai_center">
        <h1 class="body_header_title">Détail de {{$paroissien->firstname}} {{$paroissien->lastname}}</h1>

        <div class="btn_block d_flex ai_center">
            @can('paroissiens.update')
                <a href="{{route('paroissiens.update', $paroissien->id)}}" class="btn btn_main btn_outline">
                    <i class="fa fa-pencil"></i>
                    <span>Modifier</span>
                </a>
            @endcan
        </div>
    </div>
    <div class="card">
        <div class="card_content">
            @livewire('Alert')
            <div class="group">
                <h1>Informations personnelles</h1>
                <table>
                    <thead>
                        <tr>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Genre</th>
                        <th>Lieu naissance</th>
                        <th>Date naissance</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                        <td>
                            <span>{{$paroissien->firstname}}</span>
                        </td>
                        <td>
                            <span>{{$paroissien->lastname}}</span>
                        </td>
                        <td><span>{{$paroissien->genre == 'h' ? 'Homme' : 'Femme'}}</span></td>
                        <td><span>{{$paroissien->birthplace}}</span></td>
                        <td><span>{{$paroissien->birthdate}}</span></td>
                        </tr>
                    </tbody>
                </table>
                <table>
                    <thead>
                        <tr>
                        <th>Email</th>
                        <th>Adresse</th>
                        <th>Téléphone</th>
                        <th>Niveau d'étude</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                        <td>
                            <span>{{$paroissien->email}}</span>
                        </td>
                        <td>
                            <span>{{$paroissien->address}}</span>
                        </td>
                        <td><span>{{$paroissien->phone}}</span></td>
                        <td><span>{{$paroissien->school_level}}</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="group  ">
                <h1>Informations sur ses engagements</h1>
                <table>
                    <thead>
                        <tr>
                        <th>Dime</th>
                        <th>Offrande de construction</th>
                        {{-- <th>Offrande de recolte</th> --}}
                        <th>Dette dime</th>
                        <th>Dette construction</th>
                        <th>Pourcentage de versement</th>
                        <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($engagementsData as $engagement)
                            <tr>
                                <td>
                                    <span>{{formatNumber($engagement["recu"]["dime"])}} / {{formatNumber($engagement["dime"])}} FCFA</span>
                                </td>
                                <td><span>{{formatNumber($engagement["recu"]["cotisation"])}} / {{formatNumber($engagement["cotisation"])}} FCFA</span></td>
                                <td><span>{{formatNumber($engagement["recu"]["detteDime"])}} / {{formatNumber($engagement["detteDime"])}} FCFA</span></td>
                                <td><span>{{formatNumber($engagement["recu"]["detteCotisation"])}} / {{formatNumber($engagement["detteCotisation"])}} FCFA</span></td>
                                <td><span>{{$engagement["taux"]}}%</span></td>
                                <td><span>{{$engagement["taux"] == 100 ? "Terminé" : "Non achevé"}}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="group group_to_hidden">
                <h1>Informations paroisses</h1>
                <table>
                    <thead>
                        <tr>
                        <th>Ancien matricule</th>
                        <th>Nouveau matricule</th>
                        <th>Association</th>
                        <th>Catégorie</th>
                        <th>Date Baptême</th>
                        <th>Date Confirmation</th>
                        <th>Date Adhésion</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                        <td>
                            <span>{{$paroissien->old_matricule}}</span>
                        </td>
                        <td>
                            <span>{{$paroissien->new_matricule}}</span>
                        </td>
                        <td><span>{{$paroissien->association->name}}</span></td>
                        <td><span>{{$paroissien->categorie}}</span></td>
                        <td><span>{{$paroissien->baptise_date}}</span></td>
                        <td><span>{{$paroissien->confirm_date}}</span></td>
                        <td><span>{{$paroissien->adhesion_date}}</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="group group_to_hidden">
                <h1>Informations supplémentaires</h1>
                <table>
                    <thead>
                        <tr>
                        <th>Nom père</th>
                        <th>Nom mère</th>
                        <th>Statut Matrimonial</th>
                        <th>Epoux(se)</th>
                        <th>Enfant</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                        <td><span>{{$paroissien->father_name}}</span></td>
                        <td><span>{{$paroissien->mother_name}}</span></td>
                        <td><span>{{$paroissien->marital_status_text}}</span></td>
                        <td><span>{{$paroissien->wife_or_husban_name ? $paroissien->wife_or_husban_name : 'non-renseigner'}}</span></td>
                        <td><span>{{$paroissien->nb_children | 0}}</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="group group_to_hidden">
                <h1>Informations Situation</h1>
                <table>
                    <thead>
                        <tr>
                        <th>Situation</th>
                        <th>Profession</th>
                        <th>Poste occupé</th>
                        <th>Lieu de service</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                        <td><span>{{$paroissien->situation}}</span></td>
                        <td><span>{{$paroissien->job ? $paroissien->job : 'non-renseigner'}}</span></td>
                        <td><span>{{$paroissien->job_poste ? $paroissien->job_poste : 'non-renseigner'}}</span></td>
                        <td><span>{{$paroissien->service_place ? $paroissien->service_place : 'non-renseigner'}}</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <button id="hide_btn" type="button" class="btn">Voir plus</button>
        </div>
    </div>

    <div class="modal_wrapper">
        <div class="card modal">
            <div class="modal_header">
                <h1>Désactivation d'un paroissien</h1>
                <i class="fa fa-plus js_cross"></i>
            </div>
            <div class="modal_body">
                <p>Souhaitez-vous vraiment désactiver le paroissien <span>{{$paroissien->firstname}} {{$paroissien->lastname}}</span>?</p>

                <div class="btn_block add_block d_flex ai_center jc_center w_100">
                    <a href="#" class="btn btn_main btn_outline js_abort">
                        <span>Annuler</span>
                    </a>
                    <a href="#" class="btn">
                        <span>Confirmer</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('js')
    <script>
        // modal
        const modal_cross = document.querySelector('.js_cross')
        const btn_abort = document.querySelector('.js_abort')
        const modal = document.querySelector('.modal_wrapper')
        const hide_items = document.querySelectorAll('.group_to_hidden')
        const hide_btn = document.querySelector('button#hide_btn')

        modal_cross.addEventListener('click', ()=>{
            if(modal.classList.contains('show')){
                modal.classList.remove('show')
            }else{
                modal.classList.add('show')
            }
        })
        btn_abort.addEventListener('click', (e)=>{
            e.preventDefault()
            if(modal.classList.contains('show')){
                modal.classList.remove('show')
            }else{
                modal.classList.add('show')
            }
        })
        hide_btn.addEventListener('click', (e)=>{
            hide_items.forEach(h => {
                h.classList.toggle('show')
                hide_btn.innerText = h.classList.contains('show') ? 'Voir moins' : "Voir plus"
            });
        })

    </script>
@endsection
