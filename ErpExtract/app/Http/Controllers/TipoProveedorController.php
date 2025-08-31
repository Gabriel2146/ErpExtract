<?php

namespace App\Http\Controllers;

use App\Models\TipoProveedor;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TipoProveedorController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $query = TipoProveedor::query();

        // Filtrar por país para Key_User
        if ($user->role->name === 'Key_User') {
            $query->where('country', $user->country_id);
        }

        $tipos = $query->with('countryRelation')->get();
        return view('content.proveedores.tipoproveedor', compact('tipos'));
    }

    public function create()
    {
        $countries = Country::all();
        return view('tipoproveedor.create', compact('countries'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'descripcion' => 'required|string|max:30',
            'country' => 'nullable|string',
            'claseInterlocutor' => 'nullable|string|max:10',
            'socioComercial' => 'nullable|string|max:20',
            'cuentaContable' => 'nullable|string|max:25',
            'estado' => 'required|integer',
        ]);

        TipoProveedor::create($request->all());
        return redirect()->route('tipoproveedor.index')->with('success', 'Tipo de proveedor creado correctamente.');
    }

    public function edit(TipoProveedor $tipoproveedor)
    {
        $countries = Country::all();
        return view('tipoproveedor.edit', compact('tipoproveedor', 'countries'));
    }

    public function update(Request $request, TipoProveedor $tipoproveedor)
    {
        $request->validate([
            'descripcion' => 'required|string|max:30',
            'country' => 'nullable|string',
            'claseInterlocutor' => 'nullable|string|max:10',
            'socioComercial' => 'nullable|string|max:20',
            'cuentaContable' => 'nullable|string|max:25',
            'estado' => 'required|integer',
        ]);

        $tipoproveedor->update($request->all());
        return redirect()->route('tipoproveedor.index')->with('success', 'Tipo de proveedor actualizado correctamente.');
    }

    public function destroy(TipoProveedor $tipoproveedor)
    {
        $tipoproveedor->delete();
        return redirect()->route('tipoproveedor.index')->with('success', 'Tipo de proveedor eliminado.');
    }
}
