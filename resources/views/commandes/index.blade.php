@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <!-- Titre de la page -->
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-bold text-primary">📦 Gestion des Commandes</h2>
        </div>

        <!-- Tableau des commandes avec design amélioré -->
        <div class="card shadow-lg rounded border-0 mt-4 p-3 bg-gradient-light">
            <div class="card-body">
                <table class="table table-hover table-bordered text-center">
                    <thead class="bg-gradient-dark text-white rounded">
                    <tr>
                        <th>ID</th>
                        <th>Client</th>
                        <th>Total (€)</th>
                        <th>Statut</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($commandes as $commande)
                        <tr>
                            <td>#{{ $commande->id }}</td>
                            <td class="fw-bold">{{ $commande->user->name }}</td>
                            <td class="fw-bold text-success">{{ number_format($commande->total, 2) }} €</td>
                            <td>
                                <span class="badge
                                    {{ $commande->statut === 'Payée' ? 'bg-success' :
                                       ($commande->statut === 'En attente' ? 'bg-warning text-dark' :
                                       ($commande->statut === 'En cours' ? 'bg-primary' : 'bg-danger')) }}">
                                    {{ $commande->statut }}
                                </span>
                            </td>
                            <td>{{ $commande->created_at->format('d/m/Y') }}</td>
                            <td class="d-flex justify-content-center gap-2">
                                <a href="{{ route('commandes.show', $commande->id) }}" class="btn btn-outline-info btn-sm" title="Voir détails">
                                    <i class="fa-solid fa-eye"></i>
                                </a>

                                @if(Auth::user()->role === 'gestionnaire')
                                    <a href="{{ route('commandes.edit', $commande->id) }}" class="btn btn-outline-warning btn-sm" title="Modifier">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <form action="{{ route('commandes.block', $commande->id) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn btn-outline-danger btn-sm" title="Bloquer">
                                            <i class="fa-solid fa-ban"></i>
                                        </button>
                                    </form>
                                @endif

                                @if(Auth::user()->role === 'client' && Auth::id() === $commande->user_id)
                                    @if(in_array($commande->statut, ['En attente', 'En cours']))
                                        <form action="{{ route('commandes.destroy', $commande->id) }}" method="POST">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm" title="Annuler">
                                                <i class="fa-solid fa-xmark"></i>
                                            </button>
                                        </form>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-3">
            {{ $commandes->links() }}
        </div>
    </div>
@endsection
