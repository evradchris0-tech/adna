<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="/assets/logo.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    @vite('resources/css/layout.scss')
    @yield('css')
    @livewireStyles
    <title>PNM Church | @yield('title')</title>
</head>
<body>
    <main class="h_100vh d_flex w_100">
        @include('components.sidebar')
        <div class="main_contain">
            @include('components.header')
            <div class="body_contain">
                @yield('body')
            </div>
        </div>
    </main>



    @yield("modal")

    @livewireScripts

    <script>
        const btn = document.querySelector('.js_toggle')
        const cross = document.querySelector('.cross')
        const sidebar = document.querySelector('.side_bar')
        const submenus = document.querySelectorAll('li.submenu')
        cross.addEventListener('click', ()=>{
            if(sidebar.classList.contains('show')){
                sidebar.classList.remove('show')
            }else{
                sidebar.classList.add('show')
            }
        })
        btn.addEventListener('click', ()=>{
            if(sidebar.classList.contains('show')){
                sidebar.classList.remove('show')
            }else{
                sidebar.classList.add('show')
            }
        })

        if (submenus.length > 0) {
            submenus.forEach((submenu) => {
                submenu.addEventListener('click', ()=>{
                    submenu.classList.toggle('hidden')
                })
            });
        }

    </script>
    @vite(['resources/js/app.js'])
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js" defer></script>
    @yield('js')
    @stack('scripts')
</body>
</html>
