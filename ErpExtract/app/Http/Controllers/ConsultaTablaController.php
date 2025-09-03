<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Tabla;
use App\Models\Ambiente;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Jobs\ExportAndEmailJob;
use Illuminate\Support\Facades\DB;


class ConsultaTablaController extends Controller
{
    /**
     * Muestra la vista principal de consulta de tablas.
     */
    public function index()
    {
        $ambientes = Ambiente::all(); // Lista de ambientes disponibles
        $tablas = Tabla::all();
        return view('content.process.consulta-tabla', compact('ambientes', 'tablas'));
    }

    /**
     * Obtiene las tablas asociadas a un ambiente.
     */
    public function tablasPorAmbiente($ambienteId)
    {
        $tablas = Tabla::where('ambiente_id', $ambienteId)->get(['id', 'nombre', 'cds', 'entity_type']);
        return response()->json($tablas);
    }

    /**
     * Consulta datos de la tabla vía servicio OData.
     */
    public function consultaTabla(Request $request, $tablaId)
    {
        $ambienteId = $request->get('ambienteId');
        $ambiente = Ambiente::findOrFail($ambienteId);
        $tabla = Tabla::findOrFail($tablaId);
        //log::error($ambienteId);
        //log::error($tablaId);
        // Validar parámetros top y skip
        ///$top = intval($request->get('top', 50));
        //$skip = intval($request->get('skip', 0));

        $userId = Auth::id();
        $userFields = DB::table('user_table_fields')
            ->where('user_id', $userId)
            ->where('tabla_id', $tablaId)
            ->pluck('field_name')
            ->toArray();
        log::error($userFields);
        $selectFields = count($userFields) > 0 ? implode(',', $userFields) : '*';
        $format = 'json';

        //if ($top < 1 || $top > 500) $top = 50;
        //if ($skip < 0) $skip = 0;

        // Obtener credenciales del ambiente
        /* $ambiente = Ambiente::find($tabla->ambiente_id);

        if (!$ambiente) {
            return response()->json(['error' => 'Ambiente no encontrado'], 404);
        }*/
        $url = $ambiente->servidor . $ambiente->ruta . $tabla->cds . $tabla->entity_type;
        //$selectFields = 'bukrs,hbkid,hktid,waers,bankn';
        log::error($url);
        try {
            $response = Http::timeout(30) // 30 segundos
                ->withBasicAuth($ambiente->usuario, $ambiente->clave)
                ->withoutVerifying() // Ignora SSL (solo si es certificado autofirmado)
                ->get(
                    $url,
                    [
                        //'$top' => (string)$top,
                        //'$skip' => (string)$skip,
                        '$select' => $selectFields,
                        '$format' => $format,
                    ]
                );
            //log::error($response);

            if ($response->failed()) {
                return response()->json(['error' => 'Error al consultar OData', 'status' => $response->status()], 500);
            }

            $responseData = $response->json();          // Toda la respuesta JSON
            $data = $responseData['d']['results'] ?? []; // Tomar solo los resultados
            $data = array_map(function ($item) {
                unset($item['__metadata']);
                return $item;
            }, $data);

            return response()->json(['data' => $data]);
        } catch (\Exception $e) {
            //Log::error($e->getMessage());
            return response()->json([
                'error' => $e->getMessage(),
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function exportaTabla(Request $request, $tablaId)
    {
        $ambienteId = $request->get('ambienteId');
        $email = $request->get('email') ?? Auth::user()->email; // Usar email seleccionado o el del usuario
        $ambiente = Ambiente::findOrFail($ambienteId);
        $tabla = Tabla::findOrFail($tablaId);
        $top = 1000; // puedes ajustar según tu necesidad
        $skip = 0;
        $selectFields = $request->fields ?? '*'; // campos seleccionados
        $format = 'json';

        $tablaCodigo = "TABLA_{$tablaId}";
        $username = Auth::user()->username ?? 'USUARIO';

        // Obtener datos de OData
        $response = Http::timeout(30) // 30 segundos
            ->withBasicAuth($ambiente->usuario, $ambiente->clave)
            ->withoutVerifying() // Ignora SSL (solo si es certificado autofirmado)
            ->get($ambiente->servidor . $ambiente->ruta . $tabla->cds . $tabla->entity_type, [
                //'$top' => (string)$top,
                //'$skip' => (string)$skip,
                '$select' => $selectFields,
                '$format' => $format,
            ]);
        //log::error($response);

        if ($response->failed()) {
            return response()->json(['error' => 'Error al consultar OData', 'status' => $response->status()], 500);
        }

        $responseData = $response->json();          // Toda la respuesta JSON
        $data = $responseData['d']['results'] ?? []; // Tomar solo los resultados
        $data = array_map(function ($item) {
            unset($item['__metadata']);
            return $item;
        }, $data);

        $headings = $data[0] ? array_keys($data[0]) : [];

        // Despachar Job
        ExportAndEmailJob::dispatch($data, $headings, $email, $tablaCodigo, $username);

        return response()->json(['message' => 'Exportación en proceso. Recibirá un correo cuando finalice.']);
    }
}
