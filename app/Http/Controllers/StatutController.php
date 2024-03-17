<?php

namespace App\Http\Controllers;

use App\Models\Statut;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StatutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $statuts = Statut::all();
        return response()->json([
            "statuts" => $statuts
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "nom" => "required"
        ], [
            "nom.required" => 'Le Champ nom est obligatoire'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $statut = Statut::create($request->all());
        return response()->json([
            "statut" => $statut
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $statut = Statut::find($id);
        return response()->json([
            "statut" => $statut
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Statut $statut)
    {
        $validator = Validator::make($request->all(), [
            "nom" => "required"
        ], [
            "nom.required" => 'Le Champ nom est obligatoire'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $statut->update($request->all());
        return response()->json([
            "statut" => $statut
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $statut = Statut::find($id);
        if (!$statut) {
            return response()->json([
                "message" => "Statut non trouvé"
            ], 404);
        }
        $statut->delete();
        return response()->json([
            "statut" => $statut
        ], 200);
    }
}
