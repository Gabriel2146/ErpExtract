<?php

namespace App\Http\Controllers;

use App\Models\Tabla;
use App\Models\Modulo;
use Illuminate\Http\Request;

class TablaController extends Controller
{
    public function index()
    {
        $tablas = Tabla::with('modulo')->orderBy('nombre')->get();
        $modulos = Modulo::orderBy('nombre')->get();
        return view('content.config.tablas', compact('tablas', 'modulos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'modulo_id' => 'required|uuid|exists:modulos,id',
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string|max:255',
            'codigo' => 'required|string|max:25',
            'cds' => 'required|string|max:100',
            'entity_type' => 'required|string|max:100',
        ]);

        Tabla::create($request->only(['modulo_id', 'nombre', 'descripcion']));

        return redirect()->back()->with('success', 'Tabla creada correctamente');
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'modulo_id' => 'required|uuid|exists:modulos,id',
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string|max:255',
            'codigo' => 'required|string|max:25',
            'cds' => 'required|string|max:100',
            'entity_type' => 'required|string|max:100',
        ]);

        $tabla = Tabla::findOrFail($id);
        $tabla->update($request->only(['modulo_id', 'nombre', 'descripcion', 'codigo', 'cds', 'entity_type']));

        return redirect()->back()->with('success', 'Tabla actualizada correctamente');
    }

    public function destroy(string $id)
    {
        $tabla = Tabla::findOrFail($id);
        $tabla->delete();

        return redirect()->route('tablas.index')->with('success', 'Tabla eliminada correctamente');
    }
}
