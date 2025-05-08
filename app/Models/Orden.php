<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Orden extends Model
{
    protected $table = 'ordenes';

    protected $fillable = [
        'numero_orden', 'fecha_entrada', 'fecha_salida', 'cliente', 'telefono',
        'matricula', 'vehiculo', 'kilometros', 'tipo_intervencion', 'numero_factura',
        'numero_presupuesto', 'numero_resguardo', 'numero_albaran', 'situacion_vehiculo',
        'proxima_itv', 'numero_bastidor', 'descripcion_revision'
    ];

    // Relación con tareas
    public function tareas()
    {
        return $this->hasMany(Tarea::class);
    }

    // Autoasignar número de orden incremental
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($orden) {
            // Solo si no se asignó manualmente
            if (empty($orden->numero_orden)) {
                $lastOrden = self::whereNotNull('numero_orden')->orderByDesc('id')->first();
                $nextNumber = $lastOrden ? intval($lastOrden->numero_orden) + 1 : 1;
                $orden->numero_orden = str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
            }
        });
    }
}
