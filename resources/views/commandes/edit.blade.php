{{-- resources/views/commandes/edit.blade.php --}}
@extends('layouts.app')

@section('content')
    <h1>Modifier la commande</h1>
    <form action="{{ route('commandes.update', $commande->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="statut">Statut</label>
            <select name="statut" id="statut" class="form-control">
                <option value="En attente" {{ $commande->statut == 'En attente' ? 'selected' : '' }}>En attente</option>
                <option value="En préparation" {{ $commande->statut == 'En préparation' ? 'selected' : '' }}>En préparation</option>
                <option value="Expédiée" {{ $commande->statut == 'Expédiée' ? 'selected' : '' }}>Expédiée</option>
                <option value="Payée" {{ $commande->statut == 'Payée' ? 'selected' : '' }}>Payée</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Mettre à jour</button>
    </form>
@endsection
