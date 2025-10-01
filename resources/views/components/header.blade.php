<!-- @livewire('association-selector') -->
<header class="d_flex ai_center jc_between">
    <div class="header_left d_flex ai_center jc_center">
        <i class="fa-solid fa-bars js_toggle"></i>
        @auth
            <div>
                <h3><i>Bienvenue, <span>{{auth()->user()->firstname}} {{auth()->user()->lastname}}!</span></i></h3>
                <p>Année d'exercice : {{session('year')}}</p>
            </div>
        @endauth
    </div>

    <div class="header_right d_flex ai_center jc_end">

        <div class="profil d_flex ai_center jc_center">
            <div class="profil_image">
                @if (auth()->user() && auth()->user()->profil)
                    <img src="{{auth()->user()->pictures}}" alt="">
                @else
                    <img src="/assets/default.png" alt="">
                @endif
            </div>
            @auth
                <span>{{auth()->user()->firstname}} {{auth()->user()->lastname}}</span>
            @endauth
        </div>
    </div>
</header>
