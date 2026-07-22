<?php

namespace App\Http\Controllers\Responsable;

use App\Http\Controllers\Controller;
use App\Models\Stagiaire;
use Illuminate\Http\Request;

class StagiaireController extends Controller
{
    public function index(Request $request)
    {
        $query = Stagiaire::with('utilisateur');

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('recherche')) {
            $recherche = $request->recherche;
            $query->whereHas('utilisateur', function ($q) use ($recherche) {
                $q->where('nom', 'like', "%{$recherche}%")
                  ->orWhere('prenom', 'like', "%{$recherche}%");
            });
        }

        $stagiaires = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        foreach ($stagiaires as $s) {
            $nouveauScore = $s->calculerScore();
            if ($s->score_global != $nouveauScore) {
                $s->update(['score_global' => $nouveauScore]);
            }
        }

        return view('responsable.stagiaires.index', compact('stagiaires'));
    }
}