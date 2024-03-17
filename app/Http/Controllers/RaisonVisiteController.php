<?php

namespace App\Http\Controllers;

use App\Models\RaisonVisite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RaisonVisiteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $raisonVisites = RaisonVisite::all();
        return response()->json([
            'raisonVisites' => $raisonVisites
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
            'nom.require' => 'Le champ nom est obligatoire'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $raisonVisite = RaisonVisite::create($request->all());
        return response()->json([
            'raisonVisite' => $raisonVisite
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $raisonVisite = RaisonVisite::find($id);
        return response()->json([
            'raisonVisite' => $raisonVisite
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
            'nom.require' => 'Le champ nom est obligatoire'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $raisonVisite = RaisonVisite::find($id);
        $raisonVisite->update($request->all());
        return response()->json([
            'raisonVisite' => $raisonVisite
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {

        $raisonVisite = RaisonVisite::find($id);
        if (!$raisonVisite) {
            return response()->json([
                'message' => 'aucune raison de visite correspondante'
            ], 404);
        }
        $raisonVisite->delete();
        return response()->json([
            'raisonVisite' => $raisonVisite
        ], 200);
    }
}
