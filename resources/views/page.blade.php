<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title')</title>

        <!-- CSS only -->
        <link href="/css/bootstrap.min.css" rel="stylesheet">
        <link href="/css/home.css" rel="stylesheet">
        <link href="/css/relatorios.css" rel="stylesheet">

    </head>
    <body>

        <div class="container-fluid">
            <div>
                <nav class="navbar navbar-expand-lg bg-light">
                    <div class="container-fluid">
                        <a class="navbar-brand" href="/"><img src="/icon/logo.gif"></a>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav ms-auto me-3 mb-2 mb-lg-0">
                                <li class="nav-item menunav {{$menu['home']}}">
                                    <a class="nav-link" href="/"><img src="/icon/menu/agence.gif" style="">Agence</a>
                                </li>
                                <li class="nav-item menunav {{$menu['projectos']}}">
                                    <a class="nav-link" href="#"><img src="/icon/task_icon.gif">projectos</a>
                                </li>
                                <li class="nav-item menunav {{$menu['administrativos']}}">
                                    <a class="nav-link" href="#"><img src="/icon/menu/administrativo.gif" >Administrativos</a>
                                </li>
                                <li class="nav-item menunav {{$menu['comercial']}}">
                                    <a class="nav-link" href="#"><img src="/icon/menu/comercial.gif">Comercial</a>
                                </li>
                                <li class="nav-item menunav {{$menu['financeiro']}}">
                                    <a class="nav-link" href="/financeiros"><img src="/icon/menu/financeiro.gif">Financeiro</a>
                                </li>
                                <li class="nav-item menunav {{$menu['usuario']}}">
                                    <a class="nav-link" href="#"><img src="/icon/menu/usuario.gif">Usuario</a>
                                </li>
                                <li class="nav-item menunav">
                                    <a class="nav-link" href="#"><img src="/icon/menu/cancel.gif">Sair</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
            </div>

            @yield('body')

            <footer class="row m-3 border-top">
                <div class="col-sm-6 col-md-4 mb-3 mt-4">
                    <h5>Onde estamos</h5>
                    <ul class="nav flex-column">
                        <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-muted">Lorem ipsum dolor sit amet</a></li>
                        <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-muted">Lorem ipsum dolor sit amet</a></li>
                        <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-muted">Lorem ipsum dolor sit amet</a></li>
                    </ul>
                </div>

                <div class="col-sm-6 col-md-4 mb-3 mt-4">
                    <h5>O que fazemos</h5>
                    <ul class="nav flex-column">
                        <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-muted">Lorem ipsum dolor sit amet</a></li>
                        <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-muted">Lorem ipsum dolor sit amet</a></li>
                        <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-muted">Lorem ipsum dolor sit amet</a></li>
                    </ul>
                </div>

                <div class="col-sm-12 col-md-4  mb-3 mt-4">
                    <h5>Quem somos</h5>
                    <ul class="nav flex-column">
                        <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-muted">Lorem ipsum dolor sit amet</a></li>
                        <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-muted">Lorem ipsum dolor sit amet</a></li>
                        <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-muted">Lorem ipsum dolor sit amet</a></li>
                    </ul>
                </div>
            </footer>
        </div>

    <!-- JavaScript Bundle with Popper -->
        <script src="/js/jquery.slim.min.js" crossorigin="anonymous"></script>
    <script src="/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

        @yield('scripts')

    </body>
</html>
