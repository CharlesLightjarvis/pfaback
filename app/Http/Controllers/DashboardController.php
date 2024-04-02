<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Visiteur;
use App\Models\Visite;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache; // Assurez-vous d'importer le façade Cache


class DashboardController extends Controller
{
    public function getStatistiques()
    {
        $totalVisiteurs = Visiteur::count();
        $totalVisites = Visite::count();

        $visitesParStatut = Visite::join('statuts', 'visites.statut_id', '=', 'statuts.id')
            ->select('statuts.nom', DB::raw('count(*) as total'))
            ->groupBy('statuts.nom')
            ->get();

        $visitesParType = Visite::join('type_visites', 'visites.type_visite_id', '=', 'type_visites.id')
            ->select('type_visites.nom', DB::raw('count(*) as total'))
            ->groupBy('type_visites.nom')
            ->get();

        $dernieresVisites = Visite::with('visiteur', 'personnel')
            ->latest()
            ->take(5)
            ->get(['dateHeureDebut', 'dateHeureFin', 'visiteur_id', 'personnel_id']);


        $visitesParMois = Visite::select(DB::raw('MONTH(dateHeureDebut) as mois'), DB::raw('COUNT(*) as nombre'))
            ->groupBy('mois')
            ->orderBy('mois')
            ->get();

        return response()->json([
            'totalVisiteurs' => $totalVisiteurs,
            'totalVisites' => $totalVisites,
            'visitesParStatut' => $visitesParStatut,
            'visitesParType' => $visitesParType,
            'dernieresVisites' => $dernieresVisites,
            'visitesParMois' => $visitesParMois,
        ]);
    }
}
