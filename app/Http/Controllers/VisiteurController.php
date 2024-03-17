<?php

namespace App\Http\Controllers;

use App\Models\TypeVisiteur;
use App\Models\Visiteur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VisiteurController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $visiteurs = Visiteur::with(['typeVisiteur', 'visites'])->get();
        return response()->json([
            'visiteurs' => $visiteurs
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string',
            'prenom' => 'required|string',
            'telephone' => 'required|unique:visiteurs',
            'email' => 'required|email|unique:visiteurs',
            'type_visiteur_id' => 'required|exists:type_visiteurs,id'
        ], [

            'nom.required' => 'Le champ nom est obligatoire',
            'nom.string' => 'Le champ nom doit etre de type string',

            'prenom.required' => 'Le champ prenom est obligatoire',
            'prenom.string' => 'Le champ prenom doit etre de type string',

            'telephone.required' => 'Le champ telephone est obligatoire',
            'telephone.unique' => 'Ce numéro est existe déja',

            'email.required' => 'Le champ email est obligatoire',
            'email.email' => 'Le champ email doit etre une adresse email valide',
            'email.unique' => 'L\'email existe déja',

            'type_visiteur_id.required' => 'Le champ type de visiteur est obligatoire',
            'type_visiteur_id.exists' => 'Le type de visiteur existe pas'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $visiteur = Visiteur::create($request->all());
        return response()->json([
            'visiteur' => $visiteur
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $visiteur = Visiteur::with('typeVisiteur')->findOrFail($id);
        return response()->json([
            'visiteur' => $visiteur
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
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

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $visiteur = Visiteur::findOrFail($id);
        $visiteur->update($request->all());
        return response()->json([
            'visiteur' => $visiteur
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $visiteur = Visiteur::find($id);
        if (!$visiteur) {
            return response()->json([
                'message' => 'Visiteur non trouvé'
            ], 404);
        }
        $visiteur->delete();
        return response()->json([
            'message' => 'Visiteur supprimé'
        ], 200);
    }
}
