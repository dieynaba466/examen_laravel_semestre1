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
                @php $validationImpossible = false; @endphp

                @foreach(session('panier') as $livreId => $item)
                    @php
                        $livre = \App\Models\Livre::find($livreId);
                        $stockDisponible = $livre->stock ?? 0;

                        if ($item['quantite'] > $stockDisponible) {
                            $validationImpossible = true;
                        }
                    @endphp

                    <tr>
                        <td>{{ $item['titre'] }}</td>
                        <td>{{ number_format($item['prix'], 2) }} €</td>
                        <td>
                            <span class="fw-bold {{ $item['quantite'] > $stockDisponible ? 'text-danger' : 'text-success' }}">
                                {{ $item['quantite'] }}
                            </span>
                            @if($item['quantite'] > $stockDisponible)
                                <div class="text-danger fw-bold">⚠️ Stock insuffisant ! Disponible : {{ $stockDisponible }}</div>
                            @endif
                        </td>
                        <td>{{ number_format($item['prix'] * $item['quantite'], 2) }} €</td>
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
                                <button class="btn btn-outline-secondary"
                                        @if($item['quantite'] >= $stockDisponible) disabled @endif>
                                    +
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <!-- Affichage du message d'erreur si validation impossible -->
            @if($validationImpossible)
                <div class="alert alert-danger fw-bold text-center">
                    ⚠️ Impossible de valider la commande : Quantité dépassant le stock disponible !
                </div>
            @endif

            <div class="d-flex justify-content-between">
                <strong>Total : {{ number_format(array_sum(array_map(fn($item) => $item['prix'] * $item['quantite'], session('panier'))), 2) }} €</strong>

                <!-- Désactiver le bouton Valider si la validation est impossible -->
                <a href="{{ route('panier.valider') }}" class="btn btn-success @if($validationImpossible) disabled @endif">
                    ✅ Valider la commande
                </a>

                <!-- Bouton Ajouter d'autres livres -->
                <a href="{{ route('catalogue') }}" class="btn btn-primary">📖 Ajouter d'autres livres</a>

                <!-- Annulation du panier -->
                <form method="POST" action="{{ route('panier.annuler') }}">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger">❌ Annuler la commande</button>
                </form>
            </div>
        @else
            <p><a href="{{ route('catalogue') }}" class="btn btn-primary">🛍️ Passer une commande</a></p>
        @endif
    </div>
@endsection
