<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TablaCampo;
use Illuminate\Support\Str;

class TablaCampoController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'tabla_id' => 'required|uuid',
            'nombre' => 'required|string',
            'descripcion' => 'nullable|string',
        ]);

        $campo = new TablaCampo();
        $campo->id = Str::uuid();
        $campo->tabla_id = $request->tabla_id;
        $campo->nombre = strtolower($request->nombre);
        $campo->descripcion = $request->descripcion;
        $campo->obligatorio = $request->obligatorio ? 1 : 0;;
        $campo->save();

        //return back()->with('success', 'Campo agregado correctamente');
        return response()->json([
            'campo' => $campo,
            'message' => 'Campo agregado correctamente'
        ]);
    }

    public function destroy($id, Request $request)
    {
        $campo = TablaCampo::findOrFail($id);
        $campo->delete();

        $mensaje = 'Campo eliminado correctamente';

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $mensaje
            ]);
        }

        return redirect()->back()->with('success', $mensaje);
    }
}
