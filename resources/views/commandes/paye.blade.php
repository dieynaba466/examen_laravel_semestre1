@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card shadow-lg rounded border-0 bg-gradient-light p-4">
            <div class="card-header d-flex justify-content-between align-items-center bg-dark text-white rounded">
                <h1 class="fw-bold">💰 Gestion des Paiements</h1>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered text-center">
                        <thead class="bg-primary text-white rounded">
                        <tr>
                            <th>ID</th>
                            <th>Client</th>
                            <th>Montant (€)</th>
                            <th>Date de Paiement</th>
                            <th>Statut</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($commandes as $commande)
                            <tr>
                                <td>#{{ $commande->id }}</td>
                                <td class="fw-bold">{{ $commande->client->nom ?? 'Inconnu' }}</td>
                                <td class="fw-bold text-success">{{ number_format($commande->montant, 2) }} €</td>
                                <td class="text-secondary">{{ $commande->updated_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <span class="badge
                                        {{ $commande->statut === 'Payée' ? 'bg-success' :
                                           ($commande->statut === 'En attente' ? 'bg-warning text-dark' :
                                           ($commande->statut === 'Échouée' ? 'bg-danger' : 'bg-secondary')) }}">
                                        {{ $commande->statut === 'Payée' ? '✅ Payée' :
                                           ($commande->statut === 'En attente' ? '⏳ En attente' :
                                           ($commande->statut === 'Échouée' ? '❌ Échouée' : '🔄 En cours')) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                @if($commandes->isEmpty())
                    <p class="text-center fw-bold text-danger">🚫 Aucune commande payée pour le moment.</p>
                @endif
            </div>
        </div>
    </div>
@endsection
