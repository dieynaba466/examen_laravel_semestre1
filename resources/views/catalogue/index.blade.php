@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h2 class="mb-4 fw-bold text-warning text-center">📚 Catalogue des Livres</h2>

        <div class="row">
            @foreach($livres as $livre)
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-lg rounded">
                        <!-- Affichage de l'image du livre -->
                        @if($livre->image && Storage::disk('public')->exists($livre->image))
                            <img src="{{ asset('storage/' . $livre->image) }}" class="card-img-top" alt="Couverture du livre">
                        @else
                            <div class="p-5 text-center bg-light">❌ Image introuvable</div>
                        @endif

                        <div class="card-body text-center">
                            <h5 class="card-title fw-bold">{{ $livre->titre }}</h5>
                            <p class="card-text"><strong>✍️ Auteur :</strong> {{ $livre->auteur }}</p>
                            <p class="fw-bold text-success">💰 {{ number_format($livre->prix, 2) }} €</p>

                            <!-- Stock du livre -->
                            <p class="fw-bold text-{{ $livre->stock > 10 ? 'success' : ($livre->stock > 0 ? 'warning' : 'danger') }}">
                                {{ $livre->stock > 10 ? '✅ Disponible' : ($livre->stock > 0 ? '⚠️ Stock Faible' : '❌ Rupture de stock') }}
                                <br> 🛒 Quantité: {{ $livre->stock }}
                            </p>

                            <!-- Désactivation du bouton si rupture de stock -->
                            @if($livre->stock > 0)
                                <form action="{{ route('panier.ajouter', $livre->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-success w-100">🛒 Ajouter au Panier</button>
                                </form>
                            @else
                                <button class="btn btn-secondary w-100" disabled>❌ Indisponible</button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $livres->links() }}
        </div>
    </div>
@endsection
