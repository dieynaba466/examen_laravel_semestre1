<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
namespace App\Http\Controllers;

use App\Mail\OrderConfirmationMail;
use App\Mail\OrderShippedMail;
use App\Mail\OrderShippedMaills;
use App\Models\Livre;

use App\Models\Commande;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;


class SearchController extends Controller
{

    public function search(Request $request)
    {
        $query = $request->input('q');

        // Exemple de logique de recherche
        $results = Livre::where('titre', 'LIKE', "%{$query}%")
            ->orWhere('description', 'LIKE', "%{$query}%")
            ->get();

        return view('search.results', ['results' => $results]);
    }
}
