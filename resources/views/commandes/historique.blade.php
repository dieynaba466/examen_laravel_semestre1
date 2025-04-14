@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h2>Historique des Commandes</h2>

        <table class="table table-striped table-hover">
            <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Client</th>
                <th>Total</th>
                <th>Statut</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($commandes as $commande)
                <tr>
                    <td>{{ $commande->id }}</td>
                    <td>{{ $commande->client->name }}</td>
                    <td>{{ $commande->total }} €</td>
                    <td>
                        @if($commande->statut === 'Expédiée')
                            <span class="badge bg-success">Expédiée</span>
                        @elseif($commande->statut === 'En attente')
                            <span class="badge bg-warning text-dark">En attente</span>
                        @else
                            <span class="badge bg-secondary">Payée</span>
                        @endif
                    </td>
                    <td>{{ $commande->created_at->format('d/m/Y') }}</td>
                    <td>
                        <a href="{{ route('commandes.show', $commande->id) }}" class="btn btn-info btn-sm">
                            <i class="fas fa-eye"></i>
                        </a>
                        <form action="{{ route('commandes.destroy', $commande->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <div class="d-flex justify-content-center">
            {{ $commandes->links() }}
        </div>
    </div>
@endsection
