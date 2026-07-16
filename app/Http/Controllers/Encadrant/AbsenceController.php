<?php

namespace App\Http\Controllers\Encadrant;

use App\Http\Controllers\Controller;
use App\Models\Absence;
use App\Models\AffectationStage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AbsenceController extends Controller
{
    public function index()
    {
        $mesStagiairesIds = AffectationStage::where('id_encadrant', Auth::id())
            ->where('active', true)
            ->pluck('id_stagiaire');

        $absences = Absence::with('stagiaire.utilisateur')
            ->whereIn('id_stagiaire', $mesStagiairesIds)
            ->orderBy('date_absence', 'desc')
            ->paginate(10);

        return view('encadrant.absences.index', compact('absences'));
    }

    public function valider(Absence $absence)
    {
        $absence->update([
            'justifiee' => true,
            'validee_par' => Auth::id(),
        ]);

        return back()->with('success', 'Absence validée.');
    }

    public function refuser(Absence $absence)
    {
        $absence->update([
            'justifiee' => false,
            'validee_par' => Auth::id(),
        ]);

        return back()->with('success', 'Absence marquée comme non justifiée.');
    }
}