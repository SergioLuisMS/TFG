<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\HoldedContacto;

class SincronizarContactosHolded extends Command
{
    protected $signature = 'holded:sincronizar-contactos';
    protected $description = 'Sincroniza los contactos de Holded con la base de datos local';

    public function handle()
    {
        $apiKey = 'c97ee95e36b9312f9725f5d3331c62d1';
        $response = Http::withHeaders([
            'accept' => 'application/json',
            'key' => $apiKey,
        ])->get('https://api.holded.com/api/invoicing/v1/contacts');

        if (!$response->successful()) {
            $this->error('Error al obtener los contactos: ' . $response->body());
            return 1;
        }

        $contactos = $response->json();

        foreach ($contactos as $contacto) {
            HoldedContacto::updateOrCreate(
                ['holded_id' => $contacto['id']],
                [
                    'name' => $contacto['name'] ?? null,
                    'email' => $contacto['email'] ?? null,
                    'phone' => $contacto['phone'] ?? null,
                ]
            );
        }

        $this->info('Contactos sincronizados correctamente.');
        return 0;
    }
}
