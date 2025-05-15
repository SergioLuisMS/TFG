<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class HoldedController extends Controller
{
    public function buscarContacto(Request $request)
    {
        $apiKey = 'c97ee95e36b9312f9725f5d3331c62d1'; // Reemplaza con tu API Key real
        $queryNombre = $request->input('nombre'); // Ej: ?nombre=Juan Pérez

        $response = Http::withHeaders([
            'accept' => 'application/json',
            'key' => $apiKey,
        ])->get('https://api.holded.com/api/invoicing/v1/contacts');

        if (!$response->successful()) {
            return response()->json([
                'error' => 'Error al obtener los contactos',
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
        }


        $contactos = $response->json();
        $contactoEncontrado = collect($contactos)->firstWhere('name', $queryNombre);

        if ($contactoEncontrado) {
            return response()->json($contactoEncontrado);
        } else {
            return response()->json(['error' => 'Contacto no encontrado']);
        }
    }
}
