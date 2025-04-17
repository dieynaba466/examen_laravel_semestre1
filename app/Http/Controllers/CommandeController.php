<?php

namespace App\Http\Controllers;
use App\Facades\PDF;
use App\Mail\OrderShippedMailEnprep;
use App\Mail\OrderShippedMailPayer;
use App\Models\Commande;
use App\Models\Livre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderConfirmationMail;
use App\Mail\OrderShippedMail;

class CommandeController extends Controller
{
    // Pour afficher les commandes
    public function index()
    {
        if (Auth::user()->role === 'gestionnaire') {
            $commandes = Commande::latest()->paginate(10);
        } else {
            $commandes = Commande::where('user_id', Auth::id())->latest()->paginate(10);
        }

        return view('commandes.index', compact('commandes'));
    }

    // Pour le client, passer une commande
    public function store(Request $request)
    {
        $request->validate([
            'livres' => 'required|array',
            'livres.*' => 'exists:livres,id',
        ]);

        // Création de la commande avec un total temporaire de 0
        $commande = Commande::create([
            'user_id' => Auth::id(),
            'statut' => 'En attente',
            'total' => 0,
        ]);

        // Calcul du total de la commande
        $total = 0;
        foreach ($request->livres as $livre_id) {
            $livre = Livre::findOrFail($livre_id);
            $commande->livres()->attach($livre, ['quantite' => 1]);
            $total += $livre->prix;
        }

        // Mise à jour du total de la commande
        $commande->update(['total' => $total]);

        // Vérifier si l'utilisateur a une adresse email avant d'envoyer l'emai
        if (Auth::user() && Auth::user()->email) {

            try {
                Mail::to(Auth::user()->email)->send(new OrderShippedMail($commande));

            } catch (\Exception $e) {
                \Log::error('Erreur lors de l\'envoi de l\'email de confirmation : ' . $e->getMessage());
            }
        }


        return redirect()->route('commandes.index')->with('success', 'Commande passée avec succès et email envoyé.');
    }
    public function show($id)
    {
        // Vérification que l'ID est un entier
        if (!is_numeric($id)) {
            abort(404, 'ID de commande invalide.');
        }

        // Rechercher la commande
        $commande = Commande::findOrFail($id);
        return view('commandes.commandesshow', compact('commande'));
    }


    public function update(Request $request, Commande $commande)
    {
        $request->validate([
            'statut' => 'required|in:En attente,En préparation,Expédiée,Payée',
        ]);

        $commande->update(['statut' => $request->statut]);

        switch ($request->statut) {
            case 'Expédiée':
                Mail::to($commande->user->email)->send(new OrderShippedMail($commande));
                break;
            case 'En préparation':
                Mail::to($commande->user->email)->send(new OrderShippedMailEnprep($commande));
                break;
            case 'Payée':
                Mail::to($commande->user->email)->send(new OrderShippedMailPayer($commande));
                break;
        }

        return redirect()->route('commandes.index')->with('success', 'Statut de la commande mis à jour');
    }


    public function destroy(Commande $commande)
    {
        if (Auth::user()->role === 'client' && $commande->user_id !== Auth::id()) {
            abort(403, 'Accès interdit');
        }
        $commande->delete();
        return redirect()->route('commandes.index')->with('success', 'Commande annulée');
    }

    public function edit(Commande $commande)
    {
        if (Auth::user()->role !== 'gestionnaire') {
            abort(403, 'Accès interdit');
        }
        return view('commandes.edit', compact('commande'));
    }

    public function genererFacture(Commande $commande)
    {
        $pdf = PDF::loadView('factures.show', compact('commande'));
        return $pdf->download('facture-' . $commande->id . '.pdf');
    }

    public function enregistrerPaiement(Request $request, Commande $commande)
    {
        // Vérifier que seul le gestionnaire peut enregistrer un paiement
        if (Auth::user()->role !== 'gestionnaire') {
            abort(403, 'Accès interdit');
        }

        // Vérifier que la commande n'a pas déjà été payée
        if ($commande->statut === 'Payée') {
            return redirect()->route('commandes.index')->with('error', 'Cette commande est déjà payée.');
        }

        // Marquer la commande comme payée
        $commande->update([
            'statut' => 'Payée',
            'date_paiement' => now(),
            'montant_paye' => $commande->total, // Le montant payé est le total de la commande
        ]);

        return redirect()->route('commandes.index')->with('success', 'Paiement enregistré avec succès.');
    }

    public function historique()
    {
        if (Auth::user()->role === 'gestionnaire') {
            $commandes = Commande::latest()->paginate(10);
        } else {
            $commandes = Commande::where('user_id', Auth::id())->latest()->paginate(10);
        }

        return view('commandes.historique', compact('commandes'));
    }

    public function commandesPayees($commande)
    {
        if ($commande === 'all') {
            $commandes = Commande::where('statut', 'Payée')->paginate(10);
            return view('commandes.paye', compact('commandes'));
        }

        if (is_numeric($commande)) {
            $commandePayee = Commande::where('id', $commande)->where('statut', 'Payée')->first();

            if (!$commandePayee) {
                abort(404, 'Cette commande n\'est pas payée ou n\'existe pas.');
            }

            return view('commandes.detail-paye', compact('commandePayee'));
        }

        abort(404, 'Paramètre invalide.');
    }


    public function block(Commande $commande)
    {
        // Vérifier que la commande peut être bloquée (seulement "En attente" ou "En cours")
        if (!in_array($commande->statut, ['En attente', 'En cours'])) {
            return redirect()->route('commandes.index')->with('error', '⚠️ Impossible de bloquer une commande Payée ou Expédiée.');
        }

        // Mise à jour du statut de la commande
        $commande->update(['statut' => 'Bloquée']);

        return redirect()->route('commandes.index')->with('success', '✅ Commande bloquée avec succès.');
    }




}
