<?php

namespace App\Http\Controllers;

use App\Models\Personnel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PersonnelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $personnels = Personnel::all();
        return response()->json([
            'personnels' => $personnels
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required',
            'prenom' => 'required',

            'telephone' => 'required',
            'poste' => 'required',
        ], [
            'nom.required' => 'Le champ nom est obligatoire',
            'prenom.required' => 'Le champ prenom est obligatoire',

            'telephone.required' => 'Le champ téléphone est obligatoire',

            'poste.required' => 'Le champ poste est obligatoire',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $personnel = Personnel::create($request->all());
        return response()->json([
            'message' => 'personnel ajouté avec succes',
            'personnel' => $personnel
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $personnel = Personnel::find($id);
        return response()->json([
            'personnel' => $personnel
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required',
            'prenom' => 'required',

            'telephone' => 'required',
            'poste' => 'required',
        ], [
            'nom.required' => 'Le champ nom est obligatoire',
            'prenom.required' => 'Le champ prenom est obligatoire',

            'telephone.required' => 'Le champ téléphone est obligatoire',

            'poste.required' => 'Le champ poste est obligatoire',
        ]);


        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $personnel = Personnel::find($id);
        $personnel->update($request->all());
        return response()->json([
            'message' => 'personnel modifié avec succes',
            'personnel' => $personnel
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $personnel = Personnel::find($id);
        if (!$personnel) {
            return response()->json([
                'message' => 'Personnel not found'
            ], 404);
        }
        $personnel->delete();
        return response()->json([
            'message' => 'personnel supprimé avec succes',
            'personnel' => $personnel
        ], 200);
    }
}
