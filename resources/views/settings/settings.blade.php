@extends('layout')
@section('title', 'SETTINGS')
@section('css')
@vite('resources/css/paroisse.scss')
@vite('resources/css/settings.scss')
@endsection
<?php
    $years = range(strftime("%Y", time()), 2023);
?>
@section('body')
    <ul class="menu">
        <li class="active"><span>Personnel</span></li>
        <li><span>Exercice social</span></li>
    </ul>
    @if (session('type') == "config")
        @livewire('Alert')
    @endif
    @if (session('type') == "infos")
        @livewire('Alert')
    @endif
    @if (session('type') == "password")
        @livewire('Alert')
    @endif

    <div class="card_form_group form_user">
        <div class="card card_form">
            <h1>Mes infos</h1>
            <form action="{{ route('user.update.informations') }}" method="post">
                @csrf
                <div class="form_group">
                    <label for="firstname">Nom</label>
                    <div class="form_input">
                        <input type="text" name="firstname" required id="firstname" value="{{ auth()->user()->firstname }}" placeholder="Votre nom">
                    </div>
                    @error('firstname') <span class="error">{{ $message }}</span> @enderror
                </div>
                <div class="form_group">
                    <label for="lastname">Prenom</label>
                    <div class="form_input">
                        <input type="text" name="lastname" required id="lastname" value="{{ auth()->user()->lastname }}" placeholder="Votre prenom">
                    </div>
                    @error('lastname') <span class="error">{{ $message }}</span> @enderror
                </div>
                <div class="form_group">
                    <label for="email">Email</label>
                    <div class="form_input">
                        <input type="email" name="email" required id="email" value="{{ auth()->user()->email }}" placeholder="Votre email">
                    </div>
                    @error('email') <span class="error">{{ $message }}</span> @enderror
                </div>
                <div class="form_group">
                    <label for="phone">Numéro de téléphone</label>
                    <div class="form_input">
                        <input pattern="[0-9]+" type="number"  name="phone" required id="phone" value="{{ auth()->user()->phone }}" placeholder="Votre telephone">
                    </div>
                    @error('phone') <span class="error">{{ $message }}</span> @enderror
                </div>

                <button class="btn btn_main" type="submit">modifier info</button>
            </form>
        </div>
        <div class="card_form form_pwd">
            <div class="card">
                <h1>Mot de passe</h1>
                <form action="{{ route('user.update.password') }}" method="post">
                    @csrf
                    <div class="form_group">
                        <label for="oldpassword">Ancien mot de passe</label>
                        <div class="form_input password_input has_icon_block_left has_icon_right">
                            <div class="icon"><img src="/assets/icon-secure.png" alt=""></div>
                            <input type="password" required name="oldpassword" value="{{ old('oldpassword') }}" id="oldpassword" placeholder="Votre ancien mot de passe">
                            <i class="fa-solid fa-eye js_password_eye"></i>
                        </div>
                        @error('oldpassword') <span class="error">{{ $message }}</span> @enderror
                    </div>
                    <div class="form_group">
                        <label for="newpassword">Nouveau mot de passe</label>
                        <div class="form_input password_input has_icon_block_left has_icon_right">
                            <div class="icon"><img src="/assets/icon-secure.png" alt=""></div>
                            <input type="password" required name="newpassword" value="{{ old('newpassword') }}" id="newpassword" placeholder="Votre nouveau mot de passe">
                            <i class="fa-solid fa-eye js_password_eye"></i>
                        </div>
                        @error('newpassword') <span class="error">{{ $message }}</span> @enderror
                    </div>

                    <button class="btn btn_main" type="submit">modifier mot de passe</button>
                </form>
            </div>
            <div class="card">
                <h1>Mon profil</h1>
                <form action="{{ route('user.update.email') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @if (session('type') == "profil")
                        @livewire('Alert')
                    @endif
                    <div class="form_group">
                        <label for="profil">Photo de profil</label>
                        <div class="form_input">
                            <input type="file" required name="profil" id="profil">
                        </div>
                        @error('profil') <span class="error">{{ $message }}</span> @enderror
                    </div>

                    <button class="btn btn_main" type="submit">changer</button>
                </form>
            </div>
        </div>
    </div>
    <div class="card_form_group year_form d-none">
        <div class="card card_form">
            <h1>Configurez votre excercice social</h1>
            <form action="{{ route('settings.global') }}" method="post">
                @csrf
                <div class="form_group required">
                    <label for="year">Année d'exercice</label>
                    <div class="form_input">
                        <select name="year" id="year">
                            @foreach ($years as $year)
                                <option value="{{$year}}" {{$year == (session('year')+0) ? "selected" : ""}}>{{$year}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form_group">
                    <label for="trimestre">Trimestre</label>
                    <div class="form_input">
                        <select name="trimestre" id="trimestre">
                            <option value="-1" {{(session('trimestre')+0 ) == -1 ? "selected" : ""}}>Trimestre</option>
                            <option value="0" {{(session('trimestre')+0 ) == 0 ? "selected" : ""}}>Trimestre 1 (Janvier - Mars)</option>
                            <option value="1" {{(session('trimestre')+0 ) == 1 ? "selected" : ""}}>Trimestre 2 (Avril - Juin)</option>
                            <option value="2" {{(session('trimestre')+0 ) == 2 ? "selected" : ""}}>Trimestre 3 (Juillet - Septembre)</option>
                            <option value="3" {{(session('trimestre')+0 ) == 3 ? "selected" : ""}}>Trimestre 4 (Octorbre - Decembre)</option>
                        </select>
                    </div>
                </div>


                <button class="btn btn_main" type="submit">Modifier les paramettres</button>
            </form>
        </div>
    </div>




    <script>
        // script for show and hide the password
        const eye = document.querySelector('.js_password_eye')
        const input = document.querySelector('.password_input input')
        const menus = document.querySelectorAll('.menu li')
        const personal = document.querySelector('.form_user')
        const year = document.querySelector('.year_form')
        let i = 0;
        eye.addEventListener('click',()=>{
            eye.classList.remove(input.getAttribute('type') == 'text' ? 'fa-eye-slash' : 'fa-eye')
            eye.classList.add(input.getAttribute('type') == 'text' ? 'fa-eye' : 'fa-eye-slash')
            input.setAttribute('type', input.getAttribute('type') == 'text' ? 'password' : 'text')
        })
        menus.forEach((m,index) => {
            m.addEventListener('click', () => {
                if (index != i) {
                    m.classList.toggle('active')
                    menus[i].classList.toggle('active')
                    i = index
                    if (i == 1) {
                        personal.classList.toggle('d-none')
                        year.classList.toggle('d-none')
                    }
                    if (i == 0) {
                        year.classList.toggle('d-none')
                        personal.classList.toggle('d-none')
                    }
                }
            })
        });

    </script>
@endsection


