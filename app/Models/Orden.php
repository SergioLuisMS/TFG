<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Orden extends Model
{
    protected $table = 'ordenes';

    // Ya no necesitas declarar primaryKey ni keyType
    // Laravel usará automáticamente 'id' como clave primaria
    protected $fillable = [
        'numero_orden', 'fecha_entrada', 'fecha_salida', 'cliente', 'telefono',
        'matricula', 'vehiculo', 'kilometros', 'tipo_intervencion', 'numero_factura',
        'numero_presupuesto', 'numero_resguardo', 'numero_albaran', 'situacion_vehiculo',
        'proxima_itv', 'numero_bastidor', 'descripcion_revision'
    ];


    public function tareas()
    {
        return $this->hasMany(Tarea::class);
    }

}
