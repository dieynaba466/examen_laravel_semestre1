@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Liste des Livres Archivés</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Titre</th>
                            <th>Auteur</th>
                            <th>Prix</th>
                            <th>Stock</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($livres as $livre)
                            <tr>
                                <td>{{ $livre->id }}</td>
                                <td>
                                    @if($livre->image && Storage::disk('public')->exists($livre->image))
                                        <img src="{{ asset('storage/' . $livre->image) }}" alt="Image du livre" width="50" height="50">
                                    @else
                                        <span class="text-danger">Image introuvable</span>
                                    @endif
                                </td>
                                <td>{{ $livre->titre }}</td>
                                <td>{{ $livre->auteur }}</td>
                                <td>{{ $livre->prix }} €</td>
                                <td>{{ $livre->stock }}</td>
                                <td>
                                    <form action="{{ route('livres.restore', $livre->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm">🔄 Restaurer</button>
                                    </form>
                                    <form action="{{ route('livres.destroy', $livre->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">🗑️ Supprimer Définitivement</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center">
                    {{ $livres->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
