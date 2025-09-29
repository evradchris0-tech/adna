<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="/assets/logo.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    @vite('resources/css/login.scss')
    <title>PNM Church | Login</title>
</head>
<body>
    <main class="h_100vh d_flex w_100">
        <div class="img_block h_100 d_flex ai_center jc_center">
            <img src="/assets/login_back.png" alt="image de gauche de la page de login">
        </div>
        <div class="form_block d_flex ai_center jc_center">
            <img src="/assets/login_back.png" class="md" alt="image de gauche de la page de login">
            <form action="{{ route('auth.login') }}" method="post">
                <img src="/assets/logo.png" alt="logo de l'application PNM Church" class="logo">
                <h1>Connexion <span>PNM Church</span></h1>
                @csrf
                @livewire('Alert')
                <div class="form_group">
                    <label for="email">email</label>
                    <div class="form_input has_icon_block_left">
                        <div class="icon"><img src="/assets/icon-email.png" alt=""></div>
                        <input type="email" name="email" required id="email" value="{{ old('email') }}" placeholder="Votre email">
                    </div>
                    @error('email') <span class="error">{{ $message }}</span> @enderror
                </div>
                <div class="form_group">
                    <label for="password">mot de passe</label>
                    <div class="form_input password_input has_icon_block_left has_icon_right">
                        <div class="icon"><img src="/assets/icon-secure.png" alt=""></div>
                        <input type="password" required name="password" value="{{ old('password') }}" id="password" placeholder="Votre mot de passe">
                        <i class="fa-solid fa-eye js_password_eye"></i>
                    </div>
                    @error('password') <span class="error">{{ $message }}</span> @enderror
                </div>

                <a href="#">Mot de passe oublié?</a>

                <button class="btn btn_main" type="submit">Connexion</button>
            </form>
        </div>
    </main>


    <script>
        // script for show and hide the password
        const eye = document.querySelector('.js_password_eye')
        const input = document.querySelector('.password_input input')
        eye.addEventListener('click',()=>{
            eye.classList.remove(input.getAttribute('type') == 'text' ? 'fa-eye-slash' : 'fa-eye')
            eye.classList.add(input.getAttribute('type') == 'text' ? 'fa-eye' : 'fa-eye-slash')
            input.setAttribute('type', input.getAttribute('type') == 'text' ? 'password' : 'text')
        })
    </script>
</body>
</html>
