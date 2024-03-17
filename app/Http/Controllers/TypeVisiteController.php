<?php

namespace App\Http\Controllers;

use App\Models\TypeVisite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TypeVisiteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $typeVisite = TypeVisite::all();
        return response()->json([
            'typeVisite' => $typeVisite
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required'
        ], ['nom.required' => 'Le champ nom est obligatoire']);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $typeVisite = TypeVisite::create($request->all());
        return response()->json([
            'typeVisite' => $typeVisite
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $typeVisite = TypeVisite::find($id);
        return response()->json([
            'typeVisite' => $typeVisite
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  $id)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required'
        ], ['nom.required' => 'Le champ nom est obligatoire']);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $typeVisite = TypeVisite::find($id);
        $typeVisite->update($request->all());
        return response()->json([
            'typeVisite' => $typeVisite
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $typeVisite = TypeVisite::find($id);
        if (!$typeVisite) {
            return response()->json([
                'message' => 'TypeVisite non trouvé'
            ], 404);
        }
        $typeVisite->delete();
        return response()->json([
            'typeVisite' => $typeVisite
        ], 200);
    }
}
