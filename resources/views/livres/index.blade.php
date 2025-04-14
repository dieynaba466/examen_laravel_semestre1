@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="card shadow-lg rounded border-0 bg-gradient-light p-4">
            <div class="card-header d-flex justify-content-between align-items-center bg-dark text-white rounded">
                <h4 class="mb-0 fw-bold">📚 Liste des Livres</h4>
                <div>
                    <a href="{{ route('livres.archived') }}" class="btn btn-outline-light">📂 Voir Archives</a>
                    <a href="{{ route('livres.create') }}" class="btn btn-outline-warning">➕ Ajouter un Livre</a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered text-center">
                        <thead class="bg-primary text-white rounded">
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Titre</th>
                            <th>Auteur</th>
                            <th>Prix (€)</th>
                            <th>Stock</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($livres as $livre)
                            <tr>
                                <td>#{{ $livre->id }}</td>
                                <td>
                                    @if($livre->image && Storage::disk('public')->exists($livre->image))
                                        <img src="{{ asset('storage/' . $livre->image) }}" alt="Image du livre" width="60" height="60" class="rounded shadow">
                                    @else
                                        <span class="text-danger fw-bold">❌ Image introuvable</span>
                                    @endif
                                </td>
                                <td class="fw-bold">{{ $livre->titre }}</td>
                                <td>{{ $livre->auteur }}</td>
                                <td class="fw-bold text-success">{{ number_format($livre->prix, 2) }} €</td>
                                <td>
                                    <span class="badge
                                        {{ $livre->stock > 10 ? 'bg-success' :
                                           ($livre->stock > 0 ? 'bg-warning text-dark' : 'bg-danger') }}">
                                        {{ $livre->stock > 10 ? '✅ Disponible' :
                                           ($livre->stock > 0 ? '🟠 Stock Faible' : '❌ Épuisé') }}
                                    </span>
                                </td>
                                <td class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('livres.edit', $livre->id) }}" class="btn btn-outline-warning btn-sm" title="Modifier">
                                        ✏️ Modifier
                                    </a>
                                    <form action="{{ route('livres.destroy', $livre->id) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm" title="Supprimer">
                                            🗑️ Supprimer
                                        </button>
                                    </form>
                                    <form action="{{ route('livres.archive', $livre->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-secondary btn-sm" title="Archiver">
                                            📥 Archiver
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center mt-3">
                    {{ $livres->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
