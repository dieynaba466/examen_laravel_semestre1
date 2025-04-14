<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use Illuminate\Http\Request;
use PDF; // Assurez-vous d'avoir ajouté l'alias PDF dans config/app.php si nécessaire

class FactureController extends Controller
{
    public function show(Commande $commande)
    {
        // Le client ne doit consulter que ses propres commandes
        if (auth()->user()->role === 'client' && $commande->user_id !== auth()->id()) {
            abort(403, 'Accès interdit');
        }

        // Génération du PDF à partir d'une vue
        $pdf = PDF::loadView('factures.facture', compact('commande'));
        return $pdf->download('facture-' . $commande->id . '.pdf');

    }
}
