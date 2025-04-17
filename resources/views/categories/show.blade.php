@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h2 class="fw-bold text-warning">📚 Catégorie : {{ $categorie->nom }}</h2>

        <div class="row">
            @foreach($livres as $livre)
                <div class="col-md-4 mb-4">
                    <div class="card shadow-lg border-0">
                        @if($livre->image && Storage::disk('public')->exists($livre->image))
                            <img src="{{ asset('storage/' . $livre->image) }}" class="card-img-top" alt="Image du livre">
                        @else
                            <div class="p-5 text-center bg-light">❌ Image introuvable</div>
                        @endif

                        <div class="card-body">
                            <h5 class="card-title fw-bold">{{ $livre->titre }}</h5>
                            <p class="text-muted">✍️ {{ $livre->auteur }}</p>
                            <p class="fw-bold text-success">💰 {{ number_format($livre->prix, 2) }} €</p>
                            <p class="fw-bold text-{{ $livre->stock > 10 ? 'success' : ($livre->stock > 0 ? 'warning' : 'danger') }}">
                                {{ $livre->stock > 10 ? '✅ Disponible' : ($livre->stock > 0 ? '⚠️ Stock Faible' : '❌ Épuisé') }}
                                <br> 🛒 Quantité: {{ $livre->stock }}
                            </p>

                            <!-- Désactiver le bouton si le stock est épuisé -->
                            <a href="{{ $livre->stock > 0 ? route('panier.ajouter', $livre->id) : '#' }}"
                               class="btn w-100 {{ $livre->stock > 0 ? 'btn-warning' : 'btn-secondary disabled' }}">
                                🛍️ Ajouter au Panier
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center">
            {{ $livres->links() }}
        </div>
    </div>
@endsection
