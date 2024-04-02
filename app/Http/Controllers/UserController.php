<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Password;



class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $comptes = User::with("personnel")->get();
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

        $compte = new User();
        $compte->email = $request->email;
        $compte->password = Hash::make($request->password);
        $compte->personnel_id = $request->personnel_id;
        $compte->save();

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
        $compte = User::find($id);
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

        $compte = User::find($id);
        // Mettre à jour l'email et personnel_id directement
        $compte->email = $request->email;
        $compte->personnel_id = $request->personnel_id;

        // Vérifiez si un nouveau mot de passe a été fourni
        if ($request->filled('password')) {
            $compte->password = Hash::make($request->password);
        }

        $compte->save();
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
        $compte = User::find($id);
        $compte->delete();
        return response()->json([
            "message" => 'Compte supprimée avec succes',
            "compte" => $compte
        ], 200);
    }

    public function verifierEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $compte = User::where('email', $request->email)->first();

        if (!$compte) {
            return response()->json(['error' => 'L\'adresse email n\'a pas été trouvée.'], 404);
        }

        $status = Password::sendResetLink($request->only('email'));

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json(['message' => 'Un lien de réinitialisation a été envoyé à votre adresse email.'], 200);
        } else {
            return response()->json(['error' => 'Impossible d\'envoyer le lien de réinitialisation.'], 500);
        }
    }
}
