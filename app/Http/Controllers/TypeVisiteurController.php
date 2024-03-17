<?php

namespace App\Http\Controllers;

use App\Models\TypeVisiteur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TypeVisiteurController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $typeVisiteurs = TypeVisiteur::all();
        return response()->json([
            'typeVisiteurs' => $typeVisiteurs
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required',
        ], [
            'nom.required' => 'Le champ nom est obligatoire.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $typeVisiteur = TypeVisiteur::create($request->all());
        return response()->json([
            'typeVisiteur' => $typeVisiteur
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $typeVisiteur = TypeVisiteur::find($id);
        return response()->json([
            'typeVisiteur' => $typeVisiteur
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required',
        ], [
            'nom.required' => 'Le champ nom est obligatoire.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $typeVisiteur = TypeVisiteur::find($id);

        if (!$typeVisiteur) {
            return response()->json(['message' => 'Type de visiteur non trouvé'], 404);
        }

        $typeVisiteur->update($request->all());
        return response()->json([
            'typeVisiteur' => $typeVisiteur
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $typeVisiteur = TypeVisiteur::find($id);
        if (!$typeVisiteur) {
            return response()->json(['message' => 'Type de visiteur non trouvé'], 404);
        }
        $typeVisiteur->delete();
        return response()->json(['message' => 'Type de visiteur supprimé'], 200);
    }
}
