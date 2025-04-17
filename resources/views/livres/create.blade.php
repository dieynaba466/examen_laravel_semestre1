@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="card shadow-lg rounded border-0 bg-light p-4">
            <div class="card-header text-center bg-gradient text-white rounded">
                <h3 class="fw-bold">
                    @isset($livre)
                        ✏️ Modifier un Livre
                    @else
                        📖 Ajouter un Nouveau Livre
                    @endisset
                </h3>
            </div>

            <div class="card-body">
                <form action="{{ isset($livre) ? route('livres.update', $livre->id) : route('livres.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @isset($livre)
                        @method('PUT')
                    @endisset

                    <div class="mb-3">
                        <label for="titre" class="form-label">📚 Titre du Livre :</label>
                        <input type="text" class="form-control @error('titre') is-invalid @enderror" id="titre" name="titre" value="{{ old('titre', $livre->titre ?? '') }}" required>
                        @error('titre')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="auteur" class="form-label">✍️ Auteur :</label>
                        <input type="text" class="form-control @error('auteur') is-invalid @enderror" id="auteur" name="auteur" value="{{ old('auteur', $livre->auteur ?? '') }}" required>
                        @error('auteur')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="prix" class="form-label">💰 Prix (FCFA) :</label>
                            <input type="number" step="0.01" class="form-control @error('prix') is-invalid @enderror" id="prix" name="prix" value="{{ old('prix', $livre->prix ?? '') }}" required>
                            @error('prix')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="stock" class="form-label">📦 Stock :</label>
                            <input type="number" class="form-control @error('stock') is-invalid @enderror" id="stock" name="stock" value="{{ old('stock', $livre->stock ?? '') }}" required>
                            @error('stock')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="categorie_id" class="form-label">📂 Catégorie :</label>
                        <select class="form-select @error('categorie_id') is-invalid @enderror" id="categorie_id" name="categorie_id" required>
                            <option value="">Sélectionner une catégorie</option>
                            @foreach($categories as $categorie)
                                <option value="{{ $categorie->id }}" {{ old('categorie_id', $livre->categorie_id ?? '') == $categorie->id ? 'selected' : '' }}>
                                    {{ $categorie->nom }}
                                </option>
                            @endforeach
                        </select>
                        @error('categorie_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="image" class="form-label">🖼️ Image du Livre :</label>
                        <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image">
                        @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        @isset($livre->image)
                            @if(Storage::disk('public')->exists($livre->image))
                                <div class="mt-3">
                                    <p>Image actuelle :</p>
                                    <img src="{{ asset('storage/' . $livre->image) }}" class="img-thumbnail shadow-sm" width="150">
                                </div>
                            @else
                                <p class="text-danger">🚫 L'image actuelle est introuvable.</p>
                            @endif
                        @endisset
                    </div>

                    <div class="d-flex justify-content-between">
                        <button type="submit" class="btn btn-lg btn-{{ isset($livre) ? 'warning' : 'success' }}">
                            {{ isset($livre) ? '✏️ Modifier' : '💾 Enregistrer' }}
                        </button>
                        <a href="{{ route('livres.index') }}" class="btn btn-lg btn-secondary">🔙 Retour</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
