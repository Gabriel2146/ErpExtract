<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Modulo;
use App\Models\Tabla;
use App\Models\TablaCampo;
use App\Models\UserTableField;
use Illuminate\Support\Facades\Auth;

class CampoConfigController extends Controller
{
    public function index()
    {
        $modulos = Modulo::all();
        return view('content.process.campos', compact('modulos'));
    }

    public function tablasPorModulo($moduloId)
    {
        $tablas = Tabla::where('modulo_id', $moduloId)->get();
        return response()->json($tablas);
    }

    public function camposPorTabla($tablaId)
    {
        $userId = Auth::id();

        $campos = TablaCampo::where('tabla_id', $tablaId)->get();
        $seleccionados = UserTableField::where('user_id', $userId)
            ->where('tabla_id', $tablaId)
            ->pluck('field_name')
            ->toArray();

        // Marcar si el campo está seleccionado
        foreach ($campos as $campo) {
            $campo->checked = in_array($campo->nombre, $seleccionados);
        }

        return response()->json($campos);
    }

    public function store(Request $request)
    {
        $userId = Auth::id();
        $tablaId = $request->tabla_id;
        $fields = $request->fields ?? [];

        // Borrar selección anterior
        UserTableField::where('user_id', $userId)
            ->where('tabla_id', $tablaId)
            ->delete();

        // Guardar nueva selección
        foreach ($fields as $field) {
            UserTableField::create([
                'user_id' => $userId,
                'tabla_id' => $tablaId,
                'field_name' => $field
            ]);
        }

        return response()->json(['message' => 'Configuración guardada correctamente']);
    }
}
