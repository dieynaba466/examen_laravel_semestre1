@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h2 class="mb-4">🛒 Votre Panier</h2>

        @if(session()->has('panier') && count(session('panier')) > 0)
            <table class="table table-bordered">
                <thead class="table-dark">
                <tr>
                    <th>Titre</th>
                    <th>Prix Unitaire</th>
                    <th>Quantité</th>
                    <th>Total</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach(session('panier') as $livreId => $item)
                    <tr>
                        <td>{{ $item['titre'] }}</td>
                        <td>{{ $item['prix'] }} €</td>
                        <td>{{ $item['quantite'] }}</td>
                        <td>{{ $item['prix'] * $item['quantite'] }} €</td>
                        <td>
                            <form method="POST" action="{{ route('panier.update', $livreId) }}" class="d-inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="action" value="decrease">
                                <button class="btn btn-outline-secondary"
                                        @if($item['quantite'] <= 1) disabled @endif>
                                    -
                                </button>
                            </form>

                            <form method="POST" action="{{ route('panier.update', $livreId) }}" class="d-inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="action" value="increase">
                                <button class="btn btn-outline-secondary">
                                    +
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <div class="d-flex justify-content-between">
                <strong>Total : {{ array_sum(array_map(function($item) { return $item['prix'] * $item['quantite']; }, session('panier'))) }} €</strong>
                <a href="{{ route('panier.valider') }}" class="btn btn-success">Valider la commande</a>

                <!-- Bouton Ajouter d'autres livres -->
                <a href="{{ route('catalogue') }}" class="btn btn-primary">Ajouter d'autres livres</a>

                <form method="POST" action="{{ route('panier.annuler') }}">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger">Annuler la commande</button>
                </form>
            </div>
        @else
            <p><a href="{{ route('catalogue') }}" class="btn btn-primary">Passer une commande</a></p>
        @endif
    </div>
@endsection
