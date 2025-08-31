<?php

namespace App\Http\Controllers;

use App\Models\Ambiente;
use Illuminate\Http\Request;

class AmbienteController extends Controller
{
    public function index()
    {
        $ambientes = Ambiente::orderBy('nombre')->get();
        return view('content.config.ambientes', compact('ambientes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'servidor' => 'required|string|max:100',
            'ruta' => 'required|string|max:255',
            'usuario' => 'required|string|max:25',
            'clave' => 'required|string|max:255',
        ]);

        Ambiente::create($request->only(['nombre', 'servidor', 'ruta', 'usuario', 'clave']));

        return redirect()->back()->with('success', 'Ambiente creado correctamente');
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'servidor' => 'required|string|max:100',
            'ruta' => 'required|string|max:255',
            'usuario' => 'required|string|max:25',
            'clave' => 'required|string|max:255',
        ]);

        $ambiente = Ambiente::findOrFail($id);
        $ambiente->update($request->only(['nombre', 'servidor', 'ruta', 'usuario', 'clave']));

        return redirect()->back()->with('success', 'Ambiente actualizado correctamente');
    }

    public function destroy(string $id)
    {
        $ambiente = Ambiente::findOrFail($id);
        $ambiente->delete();

        return redirect()->route('ambientes.index')->with('success', 'Ambiente eliminado correctamente');
    }
}
