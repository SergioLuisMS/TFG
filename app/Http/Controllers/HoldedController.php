<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class HoldedController extends Controller
{
    /**
     * Buscar contacto en Holded por nombre.
     *
     * Esta función consulta la API de Holded para obtener la lista de contactos
     * y busca uno que coincida exactamente con el nombre recibido por parámetro.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function buscarContacto(Request $request)
    {
        // Clave API de Holded (cámbiala por seguridad si está en producción)
        $apiKey = 'c97ee95e36b9312f9725f5d3331c62d1'; // ⚠ Reemplaza con variable segura si es posible

        // Nombre del contacto buscado recibido por GET (ej: ?nombre=Juan Pérez)
        $queryNombre = $request->input('nombre');

        // Llamada a la API de contactos de Holded
        $response = Http::withHeaders([
            'accept' => 'application/json',
            'key' => $apiKey,
        ])->get('https://api.holded.com/api/invoicing/v1/contacts');

        // Verifica si la respuesta fue exitosa
        if (!$response->successful()) {
            return response()->json([
                'error' => 'Error al obtener los contactos',
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
        }

        // Lista completa de contactos
        $contactos = $response->json();

        // Busca el primer contacto con nombre exacto al buscado
        $contactoEncontrado = collect($contactos)->firstWhere('name', $queryNombre);

        // Si se encuentra el contacto, lo devuelve como JSON
        if ($contactoEncontrado) {
            return response()->json($contactoEncontrado);
        }

        // Si no se encuentra, devuelve mensaje de error
        return response()->json(['error' => 'Contacto no encontrado']);
    }
}
