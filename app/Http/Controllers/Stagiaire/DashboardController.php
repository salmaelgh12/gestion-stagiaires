<?php

namespace App\Http\Controllers\Stagiaire;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $stagiaire = Auth::user()->stagiaire;

        $joursRestants = 0;
        if ($stagiaire && $stagiaire->date_fin) {
            $aujourdhui = Carbon::now()->startOfDay();
            $fin = Carbon::parse($stagiaire->date_fin)->startOfDay();

            if ($aujourdhui->lte($fin)) {
                $joursRestants = $aujourdhui->diffInDays($fin);
            }
        }

        return view('stagiaire.dashboard', compact('stagiaire', 'joursRestants'));
    }
}