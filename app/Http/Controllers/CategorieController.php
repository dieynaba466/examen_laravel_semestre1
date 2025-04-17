<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\Livre;
use Illuminate\Http\Request;

class CategorieController extends Controller
{
    public function show($id)
    {
        $categorie = Categorie::findOrFail($id);
        $livres = Livre::where('categorie_id', $id)->paginate(12);

        return view('categories.show', compact('categorie', 'livres'));
    }

}
