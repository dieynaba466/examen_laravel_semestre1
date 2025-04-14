@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">
                    @isset($livre)
                        ✏️ Modifier un Livre
                    @else
                        ➕ Ajouter un Livre
                    @endisset
                </h4>
            </div>
            <div class="card-body">
                <form action="{{ isset($livre) ? route('livres.update', $livre->id) : route('livres.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @isset($livre)
                        @method('PUT')
                    @endisset

                    <div class="mb-3">
                        <label for="titre" class="form-label">Titre :</label>
                        <input type="text" class="form-control" id="titre" name="titre" value="{{ old('titre', $livre->titre ?? '') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="auteur" class="form-label">Auteur :</label>
                        <input type="text" class="form-control" id="auteur" name="auteur" value="{{ old('auteur', $livre->auteur ?? '') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="prix" class="form-label">Prix :</label>
                        <input type="number" step="0.01" class="form-control" id="prix" name="prix" value="{{ old('prix', $livre->prix ?? '') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="stock" class="form-label">Stock :</label>
                        <input type="number" class="form-control" id="stock" name="stock" value="{{ old('stock', $livre->stock ?? '') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="image" class="form-label">Image :</label>
                        <input type="file" class="form-control" id="image" name="image">
                        @isset($livre->image)
                            @if(Storage::disk('public')->exists($livre->image))
                                <p>Image actuelle :</p>
                                <img src="{{ asset('storage/' . $livre->image) }}" width="100" alt="Image du livre">
                            @else
                                <p class="text-danger">L'image actuelle est introuvable.</p>
                            @endif
                        @endisset
                    </div>
                    <div class="mb-3">
                        <label for="categorie_id" class="form-label">Catégorie :</label>
                        <select class="form-control" id="categorie_id" name="categorie_id" required>
                            <option value="">Sélectionner une catégorie</option>
                            @foreach($categories as $categorie)
                                <option value="{{ $categorie->id }}" {{ old('categorie_id', $livre->categorie_id ?? '') == $categorie->id ? 'selected' : '' }}>
                                    {{ $categorie->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>


                    <button type="submit" class="btn btn-{{ isset($livre) ? 'warning' : 'success' }}">
                        {{ isset($livre) ? '✏️ Modifier' : '💾 Enregistrer' }}
                    </button>
                    <a href="{{ route('livres.index') }}" class="btn btn-secondary">🔙 Retour</a>
                </form>
            </div>
        </div>
    </div>
@endsection
