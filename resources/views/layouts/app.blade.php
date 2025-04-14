<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>DIEYNA LIBRERIE</title>

    <link href="{{ asset('css/app.css') }}?v={{ time() }}" rel="stylesheet">



    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Fonts (Pacifico for a stylish brand) -->
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        .navbar {
            background: linear-gradient(135deg, #1e1e1e, #434343);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .navbar-brand {
            font-family: 'Pacifico', cursive;
            font-size: 26px;
            color: #ffcc00;
        }
        .nav-link {
            color: white !important;
            transition: all 0.3s ease-in-out;
        }
        .nav-link:hover {
            color: #ffcc00 !important;
            transform: scale(1.05);
        }
        .search-box input {
            border-radius: 20px;
            transition: all 0.3s ease-in-out;
        }
        .search-box input:focus {
            box-shadow: 0 0 8px rgba(255, 204, 0, 0.8);
        }
        .dropdown-menu {
            background: #262626;
        }
        .dropdown-item:hover {
            background: #ffcc00;
            color: #1e1e1e !important;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand" href="{{ route('stat.index') }}">
            ✨ DIEYNA LIBRERIE ✨
        </a>

        <!-- Barre de recherche (uniquement pour les clients) -->
        @auth
            @if(Auth::user()->role === 'client')
                <form class="d-flex search-box flex-grow-1 mx-3" method="GET" action="{{ route('search') }}">
                    <input class="form-control me-2" type="search" placeholder="Rechercher un livre..." name="q" aria-label="Search">
                    <button class="btn btn-warning" type="submit">Rechercher</button>
                </form>
            @endif
        @endauth

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                @auth
                    <li class="nav-item">
                        <span class="nav-link fw-bold">👋 Bonjour, {{ Auth::user()->name }}</span>
                    </li>

                    @if(Auth::user()->role === 'gestionnaire')
                        <li class="nav-item"><a class="nav-link" href="{{ route('livres.index') }}">Livres</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('commandes.index') }}">Commandes</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('commandes.commandandepaye', ['commande' => 'all']) }}">Paiements</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('stat.index') }}">Statistiques</a></li>
                    @endif

                    @if(Auth::user()->role === 'client')
                        <li class="nav-item"><a class="nav-link" href="{{ route('commandes.index') }}">Mes achats</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('panier.index') }}">Panier</a></li>
                    @endif

                    <!-- Bouton Déconnexion aligné à droite -->
                    <li class="nav-item ms-auto">
                        <a class="btn btn-outline-danger fw-bold px-4" href="{{ route('logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            Déconnexion
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST">
                            @csrf
                        </form>
                    </li>
                @else
                    <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Connexion</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Inscription</a></li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<!-- Contenu principal -->
<main class="py-4">
    @yield('content')
</main>

<!-- Footer -->
<footer class="bg-dark text-center py-3">
    <div class="container">
        <p class="text-white m-0">&copy; {{ date('Y') }} - DIEYNA LIBRERIE. Tous droits réservés.</p>
    </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
