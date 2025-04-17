<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>DIEYNA LIBRERIE</title>

    @vite(['resources/css/app.css'])

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        .navbar {
            background: linear-gradient(135deg, #13072e, #ffcc00);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        }
        .navbar-brand {
            font-family: 'Pacifico', cursive;
            font-size: 26px;
            color: white;
            text-shadow: 2px 2px 10px rgba(255, 204, 0, 0.6);
        }
        .nav-link {
            color: white !important;
            transition: all 0.3s ease-in-out;
            padding: 10px 15px;
        }
        .nav-link:hover {
            color: #ffcc00 !important;
            transform: scale(1.05);
        }
        .navbar-nav {
            display: flex;
            gap: 15px;
            align-items: center;
            flex-wrap: wrap;
            width: 100%;
        }
        .dropdown-menu {
            background: rgba(255, 204, 0, 0.9);
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand" href="
            @auth
                @if(Auth::user()->role === 'client')
                    {{ route('catalogue') }}
                @elseif(Auth::user()->role === 'gestionnaire')
                    {{ route('stat.index') }}
                @endif
            @else
                {{ url('/') }}
            @endauth
        ">
            ✨ DIEYNA LIBRERIE ✨
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                @auth
                    <li class="nav-item">
                        <span class="nav-link fw-bold">👋 {{ Auth::user()->role === 'gestionnaire' ? 'Gestionnaire' : Auth::user()->name }}</span>
                    </li>

                    @if(Auth::user()->role === 'client')
                        <li class="nav-item"><a class="nav-link" href="{{ route('commandes.index') }}">🛍️ Mes achats</a></li>
                        <li class="nav-item position-relative">
                            <a class="nav-link" href="{{ route('panier.index') }}">🛒 Panier
                                @if(session()->has('panier') && count(session('panier')) > 0)
                                    <span class="badge bg-danger">{{ count(session('panier')) }}</span>
                                @endif
                            </a>
                        </li>
                    @endif

                    @if(Auth::user()->role === 'gestionnaire')
                        <li class="nav-item"><a class="nav-link" href="{{ route('livres.index') }}">📚 Livres</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('commandes.index') }}">📦 Commandes</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('commandes.commandandepaye', ['commande' => 'all']) }}">💰 Paiements</a></li>
{{--                        <li class="nav-item"><a class="nav-link" href="{{ route('stat.index') }}">📊 Statistiques</a></li>--}}
                    @endif

                    <!-- Déconnexion : ms-auto pour coller à droite -->
                    <li class="nav-item ms-auto">
                        <a class="btn btn-outline-danger fw-bold rounded-pill px-4" href="{{ route('logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            🚪 Déconnexion
                        </a>
                    </li>
                @else
                    <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">🔑 Connexion</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">📝 Inscription</a></li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<main class="py-4">
    @yield('content')
</main>

<footer class="bg-dark text-center py-3">
    <div class="container">
        <p class="text-white m-0">&copy; {{ date('Y') }} - DIEYNA LIBRERIE. Tous droits réservés.</p>
    </div>
</footer>

<!-- Formulaire de déconnexion -->
<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
    @csrf
</form>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
