<?php

namespace App\Http\Controllers;

use App\Models\Compte;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class CompteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $comptes = Compte::with("personnel")->get();
        return response()->json([
            "comptes" => $comptes
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Log::info("message", $request->all());
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:comptes,email',
            'password' => 'required|min:6',
            'personnel_id' => 'required|exists:personnels,id'
        ], [
            'email.required' => 'Le champ email est obligatoire',
            'email.email' => 'Le champ email doit être une adresse email valide',
            'email.unique' => 'Cette adresse email est déjà utilisée',
            'password.required' => 'Le champ mot de passe est obligatoire',
            'password.min' => 'Le mot de passe doit faire au moins 6 caractères',
            'personnel_id.required' => 'Le champ personnel est obligatoire',
            'personnel_id.exists' => 'Le personnel n\'existe pas'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $compte = Compte::create($request->all());
        return response()->json([
            "message" => 'Compte crée avec succes',
            "compte" => $compte
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $compte = Compte::find($id);
        return response()->json([
            "compte" => $compte
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:comptes,email,' . $id,
            'password' => 'required|min:6',
            'personnel_id' => 'required|exists:personnels,id'
        ], [
            'email.required' => 'Le champ email est obligatoire',
            'email.email' => 'Le champ email doit être une adresse email valide',
            'email.unique' => 'Cette adresse email est déjà utilisée',
            'password.required' => 'Le champ mot de passe est obligatoire',
            'password.min' => 'Le mot de passe doit faire au moins 6 caractères',
            'personnel_id.required' => 'Le champ personnel est obligatoire',
            'personnel_id.exists' => 'Le personnel n\'existe pas'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $compte = Compte::find($id);
        $compte->update($request->all());
        return response()->json([
            "message" => 'Compte modifié avec succes',
            "compte" => $compte
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $compte = Compte::find($id);
        $compte->delete();
        return response()->json([
            "message" => 'Compte supprimée avec succes',
            "compte" => $compte
        ], 200);
    }
}
