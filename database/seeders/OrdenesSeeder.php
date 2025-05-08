<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class OrdenesSeeder extends Seeder
{
    public function run(): void
    {
        $ordenes = [
            [
                'matricula' => '9990CKY',
                'vehiculo' => 'OPEL VIVARO',
                'cliente' => 'LUIS ANGEL',
                'tipo_intervencion' => 'MANTENIMIENTO',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'matricula' => '2032FMW',
                'vehiculo' => 'PEUGEOT EXPERT',
                'cliente' => 'ELECTRICIDAD SILVA',
                'tipo_intervencion' => 'SUSTITUCIÓN AMORTIGUADORES',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'matricula' => '9162MVD',
                'vehiculo' => 'RENAULT TRAFIC',
                'cliente' => 'JESUS LARRAÑAGA',
                'tipo_intervencion' => 'LATIGUILLOS',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'matricula' => '7534LKT',
                'vehiculo' => 'FORD TRANSIT',
                'cliente' => 'PACO',
                'tipo_intervencion' => 'REPARACION PUERTA',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'matricula' => '1830FHN',
                'vehiculo' => 'RENAULT MASTER',
                'cliente' => 'REYES',
                'tipo_intervencion' => 'REPARACION MOTOR',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'matricula' => '3241BZR',
                'vehiculo' => 'FIAT DUCATO',
                'cliente' => 'ANA GOMEZ',
                'tipo_intervencion' => 'CAMBIO EMBRAGUE',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'matricula' => '4963KMG',
                'vehiculo' => 'PEUGEOT BOXER',
                'cliente' => 'ANDRES',
                'tipo_intervencion' => 'REVISION ITV',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'matricula' => '4567CBB',
                'vehiculo' => 'MERCEDES SPRINTER',
                'cliente' => 'HERMANOS GARCIA',
                'tipo_intervencion' => 'CAMBIO ACEITE Y FILTROS',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'matricula' => '8765DBK',
                'vehiculo' => 'CITROEN JUMPER',
                'cliente' => 'ANTONIO',
                'tipo_intervencion' => 'FRENOS',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'matricula' => '7834GFD',
                'vehiculo' => 'IVECO DAILY',
                'cliente' => 'RECAMBIOS LUCIA',
                'tipo_intervencion' => 'REVISIÓN GENERAL',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        DB::table('ordenes')->insert($ordenes);
    }
}
