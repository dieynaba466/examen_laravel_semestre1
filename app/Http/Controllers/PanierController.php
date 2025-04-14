<?php

namespace App\Http\Controllers;

use App\Mail\OrderConfirmationMail;
use App\Mail\OrderShippedMail;
use App\Mail\OrderShippedMaills;
use App\Models\Livre;

use App\Models\Commande;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PanierController extends Controller
{
    // Afficher le panier
    public function index()
    {
        $panier = session()->get('panier', []);
        return view('panier.index', compact('panier'));
    }

    // Ajouter un livre au panier
    public function ajouter(Livre $livre)
    {

        $panier = session()->get('panier', []);

        // Si le livre est déjà dans le panier, on augmente la quantité
        if (isset($panier[$livre->id])) {
            $panier[$livre->id]['quantite']++;
        } else {
            // Sinon, on l'ajoute au panier
            $panier[$livre->id] = [
                'titre' => $livre->titre,
                'prix' => $livre->prix,
                'quantite' => 1,
            ];
        }

        // Mettre à jour le panier dans la session
        session()->put('panier', $panier);

        return redirect()->route('panier.index')->with('success', 'Livre ajouté au panier');
    }

    public function valider()
    {
        // Réduire les stocks des livres commandés
        $panier = session()->get('panier', []);
        $total = 0;

        // Créer la commande
        $commande = Commande::create([
            'user_id' => Auth::id(),
            'statut' => 'En attente',
            'total' => 0, // Le total sera mis à jour après avoir ajouté les livres
        ]);

        // Attacher les livres à la commande et calculer le total
        foreach ($panier as $livreId => $item) {
            $livre = Livre::find($livreId);
            // Vérifier si le livre existe en stock
            if ($livre && $livre->stock >= $item['quantite']) {
                // Réduire le stock du livre
                $livre->stock -= $item['quantite'];
                $livre->save();

                // Attacher le livre à la commande avec la quantité
                $commande->livres()->attach($livre, ['quantite' => $item['quantite']]);
                $total += $livre->prix * $item['quantite'];
            }
        }

        // Mettre à jour le total de la commande
        $commande->update(['total' => $total]);

        // Vider le panier
        session()->forget('panier');
        // Vérifier si l'utilisateur a une adresse email avant d'envoyer l'emai
        if (Auth::user() && Auth::user()->email) {

            try {
                Mail::to(Auth::user()->email)->send(new OrderShippedMaills($commande));

            } catch (\Exception $e) {
                \Log::error('Erreur lors de l\'envoi de l\'email de confirmation : ' . $e->getMessage());
            }
        }

        return redirect()->route('commandes.index')->with('success', 'Votre commande a été validée avec succès');
    }

    public function update(Request $request, $id)
    {
        $panier = session('panier', []);

        if (isset($panier[$id])) {
            if ($request->input('action') === 'increase') {
                $panier[$id]['quantite']++;
            } elseif ($request->input('action') === 'decrease' && $panier[$id]['quantite'] > 1) {
                $panier[$id]['quantite']--;
            }
            session(['panier' => $panier]);
        }

        return redirect()->back();
    }


    public function countItems()
    {
        $panier = session('panier', []);
        return count($panier);
    }



    public function genererFacture()
    {
        $panier = session()->get('panier', []);

        // Générer la vue de la facture avec les informations du panier
        $pdf = PDF::loadView('factures.facture', compact('panier'));

        // Télécharger la facture en PDF
        return $pdf->download('facture.pdf');
    }

    public function annuler()
    {
        // Supprime tous les articles du panier
        session()->forget('panier');

        // Redirige l'utilisateur avec un message de confirmation
        return redirect()->route('panier.index')->with('message', 'Votre commande a été annulée.');
    }

}

