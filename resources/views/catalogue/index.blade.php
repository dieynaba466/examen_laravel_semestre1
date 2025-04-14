@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h2 class="mb-4">📚 Catalogue des Livres</h2>

        <div class="row">
            @foreach($livres as $livre)
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <img src="{{ asset('storage/' . $livre->image) }}" class="card-img-top" alt="Couverture du livre">
                        <div class="card-body">
                            <h5 class="card-title">{{ $livre->titre }}</h5>
                            <p class="card-text"><strong>Auteur :</strong> {{ $livre->auteur }}</p>
                            <p class="card-text"><strong>Prix :</strong> {{ $livre->prix }} €</p>
                            <p class="card-text">{{ Str::limit($livre->description, 80) }}</p>

                            <!-- Ajouter au panier -->
                            <form action="{{ route('panier.ajouter', $livre->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success">🛒 Ajouter au Panier</button>
                            </form>
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
