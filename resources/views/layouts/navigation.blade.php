@auth
    <nav class="bg-white shadow p-4 flex justify-between">
        <div class="flex space-x-4">
            @if(Auth::user()->role === 'gestionnaire')
                <a href="{{ route('livres.index') }}" class="mr-4">📚 Gérer les Livres</a>
                <a href="{{ route('commandes.index') }}" class="mr-4">📦 Gérer les Commandes</a>
                <a href="#" class="mr-4">💰 Gestion des Paiements</a>
                <a href="#" class="mr-4">📊 Statistiques</a>
            @else
                <a href="{{ route('catalogue') }}" class="mr-4">📚 Catalogue</a>
                <a href="{{ route('commandes.index') }}" class="mr-4">📦 Mes Commandes</a>
            @endif
        </div>

        <div class="flex items-center space-x-4">
            <!-- Profil -->
            <a href="{{ route('profile.edit') }}" class="text-gray-700 dark:text-gray-300">👤 {{ Auth::user()->name }}</a>

            <!-- Déconnexion -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-red-500 hover:underline">🚪 Déconnexion</button>
            </form>
        </div>
    </nav>
@endauth
