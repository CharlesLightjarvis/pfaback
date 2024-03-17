<?php

namespace App\Http\Controllers;

use App\Models\Visite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Visiteur;
use Illuminate\Support\Facades\Log;

class VisiteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $visites = Visite::with(['personnel', 'typeVisite', 'raisonVisite', 'statut', 'visiteur'])->get();
        return response()->json(['visites' => $visites], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validation des informations générales de la visite
        $visiteData = $request->only(['dateHeureDebut', 'dateHeureFin', 'raison_visite_id', 'personnel_id', 'type_visite_id', 'statut_id', 'details']);
        $visiteValidator = Validator::make($visiteData, [
            // Règles de validation pour la visite
            'dateHeureDebut' => 'required|date',
            'dateHeureFin' => 'required|date|after_or_equal:dateHeureDebut',
            'raison_visite_id' => 'required|exists:raison_visites,id',
            'personnel_id' => 'required|exists:personnels,id',
            'type_visite_id' => 'required|exists:type_visites,id',
            'statut_id' => 'required|exists:statuts,id',
            'details' => 'nullable|string',
        ], [
            'dateHeureDebut.required' => 'La date de début est obligatoire',
            'dateHeureDebut.date' => 'La date de début doit être une date valide',

            'dateHeureFin.required' => 'La date de fin est obligatoire',
            'dateHeureFin.date' => 'La date de fin doit être une date valide',
            'dateHeureFin.after_or_equal' => 'La date de fin doit être égale ou après la date de début',

            'raison_visite_id.required' => 'La raison de la visite est obligatoire',
            'raison_visite_id.exists' => 'La raison de la visite sélectionnée est invalide',

            'personnel_id.required' => 'L\'identifiant du personnel est obligatoire',
            'personnel_id.exists' => 'Le personnel sélectionné est invalide',

            'type_visite_id.required' => 'Le type de visite est obligatoire',
            'type_visite_id.exists' => 'Le type de visite sélectionné est invalide',

            'statut_id.required' => 'Le statut de la visite est obligatoire',
            'statut_id.exists' => 'Le statut sélectionné est invalide',

            'details.string' => 'Les détails doivent être une chaîne de caractères',
        ]);

        if ($visiteValidator->fails()) {
            return response()->json(['errors' => $visiteValidator->errors()], 422);
        }

        // Assumer que $request->visiteurs est un tableau de données visiteur
        $visiteurData = $request->visiteur;
        $visiteurValidator = Validator::make($visiteurData, [
            'nom' => 'required|string',
            'prenom' => 'required|string',
            'telephone' => 'required',
            'email' => 'required|email',
            'type_visiteur_id' => 'required|exists:type_visiteurs,id'
        ], [

            'nom.required' => 'Le champ nom est obligatoire',
            'nom.string' => 'Le champ nom doit etre de type string',

            'prenom.required' => 'Le champ prenom est obligatoire',
            'prenom.string' => 'Le champ prenom doit etre de type string',

            'telephone.required' => 'Le champ telephone est obligatoire',

            'email.required' => 'Le champ email est obligatoire',
            'email.email' => 'Le champ email doit etre une adresse email valide',

            'type_visiteur_id.required' => 'Le champ type de visiteur est obligatoire',
            'type_visiteur_id.exists' => 'Le type de visiteur existe pas'
        ]);

        if ($visiteurValidator->fails()) {
            return response()->json(['errors' => $visiteurValidator->errors()], 422);
        }

        DB::beginTransaction();
        try {
            // Créer ou récupérer le visiteur
            $visiteur = Visiteur::updateOrCreate(
                ['email' => $visiteurData['email']],
                $visiteurData
            );

            // Ajouter l'ID du visiteur aux données de la visite
            $visiteData['visiteur_id'] = $visiteur->id;

            // Créer la visite
            $visite = Visite::create($visiteData);

            DB::commit();
            return response()->json(['message' => 'La visite et le visiteur ont été enregistrés avec succès', 'visite' => $visite], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Une erreur est survenue lors de l\'enregistrement.'], 500);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $visite = Visite::find($id);
        return response()->json([
            'visite' => $visite
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validation des informations générales de la visite
        $visiteData = $request->only(['dateHeureDebut', 'dateHeureFin', 'raison_visite_id', 'personnel_id', 'type_visite_id', 'statut_id', 'details']);
        $visiteurData = $request->get('visiteur');


        $visiteValidator = Validator::make($visiteData, [
            // Règles de validation pour la visite
            'dateHeureDebut' => 'required|date',
            'dateHeureFin' => 'required|date|after_or_equal:dateHeureDebut',
            'raison_visite_id' => 'required|exists:raison_visites,id',
            'personnel_id' => 'required|exists:personnels,id',
            'type_visite_id' => 'required|exists:type_visites,id',
            'statut_id' => 'required|exists:statuts,id',
            'details' => 'nullable|string',
        ], [
            'dateHeureDebut.required' => 'La date de début est obligatoire',
            'dateHeureDebut.date' => 'La date de début doit être une date valide',

            'dateHeureFin.required' => 'La date de fin est obligatoire',
            'dateHeureFin.date' => 'La date de fin doit être une date valide',
            'dateHeureFin.after_or_equal' => 'La date de fin doit être égale ou après la date de début',

            'raison_visite_id.required' => 'La raison de la visite est obligatoire',
            'raison_visite_id.exists' => 'La raison de la visite sélectionnée est invalide',

            'personnel_id.required' => 'L\'identifiant du personnel est obligatoire',
            'personnel_id.exists' => 'Le personnel sélectionné est invalide',

            'type_visite_id.required' => 'Le type de visite est obligatoire',
            'type_visite_id.exists' => 'Le type de visite sélectionné est invalide',

            'statut_id.required' => 'Le statut de la visite est obligatoire',
            'statut_id.exists' => 'Le statut sélectionné est invalide',

            'details.string' => 'Les détails doivent être une chaîne de caractères',
        ]);

        if ($visiteValidator->fails()) {
            return response()->json(['errors' => $visiteValidator->errors()], 422);
        }

        // Assumer que $request->visiteurs est un tableau de données visiteur
        $visiteurData = $request->visiteur;
        $visiteurValidator = Validator::make($visiteurData, [
            'nom' => 'required|string',
            'prenom' => 'required|string',
            'telephone' => 'required',
            'email' => 'required|email',
            'type_visiteur_id' => 'required|exists:type_visiteurs,id'
        ], [

            'nom.required' => 'Le champ nom est obligatoire',
            'nom.string' => 'Le champ nom doit etre de type string',

            'prenom.required' => 'Le champ prenom est obligatoire',
            'prenom.string' => 'Le champ prenom doit etre de type string',

            'telephone.required' => 'Le champ telephone est obligatoire',

            'email.required' => 'Le champ email est obligatoire',
            'email.email' => 'Le champ email doit etre une adresse email valide',

            'type_visiteur_id.required' => 'Le champ type de visiteur est obligatoire',
            'type_visiteur_id.exists' => 'Le type de visiteur existe pas'
        ]);

        if ($visiteurValidator->fails()) {
            return response()->json(['errors' => $visiteurValidator->errors()], 422);
        }

        DB::beginTransaction();
        try {
            // Mettre à jour ou créer le visiteur
            $visiteur = Visiteur::updateOrCreate(['id' => $visiteurData['id']], $visiteurData);

            // Ajouter ou mettre à jour la visite avec l'ID du visiteur
            $visite = Visite::findOrFail($id);
            $visiteData['visiteur_id'] = $visiteur->id; // S'assurer que cette colonne existe dans votre table `visites`
            $visite->update($visiteData);

            DB::commit();
            return response()->json(['message' => 'Visite mise à jour avec succès', 'visite' => $visite], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Erreur lors de la mise à jour de la visite'], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $visite = Visite::find($id);
        if (!$visite) {
            return response()->json([
                'message' => 'Visite non trouvée'
            ], 404);
        }
        $visite->delete();
        return response()->json([
            'visite' => $visite
        ], 200);
    }
}
