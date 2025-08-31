<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Country;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class CountryController extends Controller
{
    /**
     * Listar todos los países
     */
    public function index()
    {
        $user = Auth::user();

        // Admin ve todos los países
        if ($user->role?->name === 'Administrador') {
            $countries = Country::orderBy('name')->get();
        } else {
            // Key_user ve solo su país asignado
            $countries = Country::where('id', $user->country_id)->get();
        }

        return view('content.ubicaciones.pais', compact('countries'));
    }

    /**
     * Guardar un nuevo país
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'nullable|string|max:10',
        ]);

        Country::create([
            'id' => Str::uuid(),
            'name' => $request->name,
            'code' => $request->code,
        ]);

        return redirect()->back()->with('success', 'País creado correctamente.');
    }

    /**
     * Actualizar país
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'nullable|string|max:10',
        ]);

        $country = Country::findOrFail($id);
        $country->update([
            'name' => $request->name,
            'code' => $request->code,
        ]);

        return redirect()->back()->with('success', 'País actualizado correctamente.');
    }

    /**
     * Eliminar país
     */
    public function destroy($id)
    {
        $country = Country::findOrFail($id);
        $country->delete();

        return redirect()->back()->with('success', 'País eliminado correctamente.');
    }
}
