<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Modulo;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class ModuloController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {        
        $modulos = Modulo::orderBy('nombre')->get();
        return view('content.config.modulos', compact('modulos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:50',
            'codigo' => 'required|string|max:5',
        ]);

        Modulo::create([
            'nombre' => $request->nombre,
            'codigo' => $request->codigo
        ]);

         return redirect()->back()->with('success', 'Modulo creado correctamente');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:50',
            'codigo' => 'required|string|max:5',
        ]);

        $modulo = Modulo::findOrFail($id);
        $modulo->update([
            'nombre' => $request->nombre,
            'codigo' => $request->codigo
        ]);
        return redirect()->back()->with('success', 'Modulo actualizado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $modulo = Modulo::findOrFail($id);
        $modulo->delete();
        return redirect()->route('modulos.index')->with('success', 'Modulo Eliminado correctamente');
    }
}
