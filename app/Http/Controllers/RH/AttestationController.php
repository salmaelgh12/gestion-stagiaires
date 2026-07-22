<?php

namespace App\Http\Controllers\RH;

use App\Http\Controllers\Controller;
use App\Models\Attestation;

class AttestationController extends Controller
{
    public function index()
    {
        $attestations = Attestation::with('stagiaire.utilisateur')
            ->orderBy('date_generation', 'desc')
            ->paginate(10);

        return view('rh.attestations.index', compact('attestations'));
    }
}