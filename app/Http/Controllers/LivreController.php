<?php

namespace App\Http\Controllers;

use App\Models\Livre;
use App\Models\Categorie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LivreController extends Controller
{
    public function index()
    {
        $livres = Livre::where('archived', false)->paginate(10);
        return view('livres.index', compact('livres'));
    }
    public function archived()
    {
        $livres = Livre::where('archived', true)->paginate(10);
        return view('livres.archived', compact('livres'));
    }


    public function create()
    {
        $categories = Categorie::all();
        return view('livres.create', compact('categories'));
    }

    public function archive(Livre $livre)
    {
        $livre->update(['archived' => true]);
        return redirect()->route('livres.index')->with('success', 'Livre archivé avec succès.');
    }


    public function restore(Livre $livre)
    {
        $livre->update(['archived' => false]);
        return redirect()->route('livres.archived')->with('success', 'Livre restauré avec succès.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'auteur' => 'required|string|max:255',
            'prix' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'categorie_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|max:2048'
        ]);

        $imagePath = $request->file('image') ? $request->file('image')->store('livres', 'public') : null;

        Livre::create([
            'titre' => $request->titre,
            'auteur' => $request->auteur,
            'prix' => $request->prix,
            'description' => $request->description,
            'stock' => $request->stock,
            'categorie_id' => $request->categorie_id,
            'image' => $imagePath
        ]);

        return redirect()->route('livres.index')->with('success', 'Livre ajouté avec succès');
    }

    public function show(Livre $livre)
    {
        return view('livres.show', compact('livre'));
    }

    public function edit(Livre $livre)
    {
        $categories = Categorie::all();
        return view('livres.create', compact('livre', 'categories'));
    }

    public function update(Request $request, Livre $livre)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'auteur' => 'required|string|max:255',
            'prix' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'categorie_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('image')) {
            if ($livre->image) {
                Storage::delete('public/' . $livre->image);
            }
            $imagePath = $request->file('image')->store('livres', 'public');
        } else {
            $imagePath = $livre->image;
        }

        $livre->update([
            'titre' => $request->titre,
            'auteur' => $request->auteur,
            'prix' => $request->prix,
            'description' => $request->description,
            'stock' => $request->stock,
            'categorie_id' => $request->categorie_id,
            'image' => $imagePath
        ]);

        return redirect()->route('livres.index')->with('success', 'Livre mis à jour');
    }

    public function destroy(Livre $livre)
    {
        if ($livre->image) {
            Storage::delete('public/' . $livre->image);
        }

        $livre->delete();
        return redirect()->route('livres.index')->with('success', 'Livre supprimé');
    }

    public function catalogue()
    {
        // Récupérer tous les livres, y compris ceux en rupture de stock
        $livres = Livre::paginate(12);

        return view('catalogue.index', compact('livres'));
    }



}
