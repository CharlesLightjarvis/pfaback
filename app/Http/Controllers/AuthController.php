<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            // Génération d'un jeton d'accès pour l'utilisateur
            $token = $user->createToken('Personal Access Token')->plainTextToken;


            // Retourner le jeton d'accès et l'utilisateur connecté
            return response()->json(['token' => $token, 'user' => $user], 200);
        }


        // Retourner une erreur si l'authentification a échoué
        return response()->json(['error' => 'Unauthorized'], 401);
    }



    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Déconnexion réussie']);
    }
}
