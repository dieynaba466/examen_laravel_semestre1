@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h2>Détails de la commande #{{ $commande->id }}</h2>

        <table class="table table-bordered">
            <thead class="table-dark">
            <tr>
                <th>Titre</th>
                <th>Prix Unitaire</th>
                <th>Quantité</th>
                <th>Total</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($commande->livres as $livre)
                <tr>
                    <td>{{ $livre->titre }}</td>
                    <td>{{ $livre->prix }} €</td>
                    <td>{{ $livre->pivot->quantite }}</td>
                    <td>{{ $livre->prix * $livre->pivot->quantite }} €</td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <div class="d-flex justify-content-between align-items-center mt-3">
            <strong>Total : {{ $commande->total }} €</strong>
            <span>Statut :
                @if($commande->statut === 'Expédiée')
                    <span class="badge bg-success">Expédiée</span>
                @elseif($commande->statut === 'En attente')
                    <span class="badge bg-warning text-dark">En attente</span>
                @else
                    <span class="badge bg-secondary">Payée</span>
                @endif
            </span>
        </div>

        @if(Auth::user()->role === 'gestionnaire')
            <div class="d-flex justify-content-between mt-4">
                <!-- Bouton Voir Historique -->
                <a href="{{ route('commandes.historique') }}" class="btn btn-secondary">
                    <i class="fas fa-history"></i> Voir Historique
                </a>

                <!-- Générer la facture -->
                @if($commande->statut === 'Expédiée')
                    <form action="{{ route('commandes.genererFacture', $commande->id) }}" method="GET" class="d-inline">
                        <button type="submit" class="btn btn-info">
                            <i class="fas fa-file-invoice"></i> Générer la facture
                        </button>
                    </form>
                @endif
            </div>
        @endif
    </div>
@endsection
