<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\Livre;
use Illuminate\Http\Request;
use PDF;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DailySalesExport;

class DashboardController extends Controller
{
    public function statistiques()
    {
        // Commandes du jour
        $commandesDuJour = Commande::whereDate('created_at', today())->count();

        // Commandes validées
        $commandesValidees = Commande::whereDate('updated_at', today())
            ->where('statut', 'Payée')
            ->count();

        // Recettes journalières
        $recettes = Commande::whereDate('updated_at', today())
            ->where('statut', 'Payée')
            ->sum('total');

        // Nombre de commandes par mois (converti en tableau)
        $commandesParMois = Commande::selectRaw('EXTRACT(MONTH FROM created_at) as mois, COUNT(*) as total')
            ->groupByRaw('EXTRACT(MONTH FROM created_at)')
            ->orderByRaw('EXTRACT(MONTH FROM created_at)')
            ->pluck('total', 'mois')
            ->toArray();

        // Livres vendus par catégorie (converti en tableau)
        $livresParCategorie = Livre::join('commande_livre', 'livres.id', '=', 'commande_livre.livre_id')
            ->join('categories', 'livres.categorie_id', '=', 'categories.id')
            ->selectRaw('categories.nom as categorie, SUM(commande_livre.quantite) as total')
            ->groupBy('categories.nom')
            ->pluck('total', 'categorie')
            ->toArray();

        // Retourner toutes les variables à la vue
        return view('stat.index', compact(
            'commandesDuJour',
            'commandesValidees',
            'recettes',
            'commandesParMois',
            'livresParCategorie'
        ));
    }

    public function exportPdf()
    {
        // Récupère les commandes "Payée" du jour
        $ventes = Commande::whereDate('updated_at', today())
            ->where('statut', 'Payée')
            ->get();

        $pdf = PDF::loadView('pdf.daily_sales', compact('ventes'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('ventes_journalieres_'.today()->format('Y_m_d').'.pdf');
    }


    public function exportExcel()
    {
        return Excel::download(new DailySalesExport, 'ventes_journalieres_'.today()->format('Y_m_d').'.xlsx');
    }
}
