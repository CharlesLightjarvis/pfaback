<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Validation des données d'entrée
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Tentative de connexion de l'utilisateur
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            // Récupération de l'utilisateur authentifié
            $user = Auth::user();

            // Charger la relation 'personnel' pour cet utilisateur
            $user->load('personnel');

            // Génération d'un jeton d'accès pour l'utilisateur
            $token = $user->createToken('Personal Access Token')->plainTextToken;

            // Retourner le jeton d'accès, l'utilisateur connecté et ses données personnelles associées
            return response()->json(['token' => $token, 'user' => $user], 200);
        }

        // Retourner une erreur si l'authentification a échoué
        return response()->json(['error' => 'Unauthorized'], 401);
    }


    public function compteConnecte()
    {
        $user = Auth::user(); // Récupère l'utilisateur authentifié
        $compte = $user->load('personnel'); // Charge les relations personnel si nécessaire

        return response()->json([
            "compte" => $compte
        ], 200);
    }


    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Déconnexion réussie']);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            Log::error('Erreur de correspondance du mot de passe actuel pour l\'utilisateur : ' . $user->id);
            return response()->json(['error' => 'Le mot de passe actuel ne correspond pas.'], 400);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        Log::info('Mot de passe mis à jour avec succès pour l\'utilisateur : ' . $user->id);

        return response()->json(['message' => 'Mot de passe mis à jour avec succès.']);
    }
}
