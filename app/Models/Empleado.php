<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    // Relación con asistencias
    public function asistencias()
    {
        return $this->hasMany(Asistencia::class);
    }

    // Relación con tareas asignadas
    public function tareas()
    {
        return $this->hasMany(Tarea::class);
    }

    protected $fillable = [
        'nombre',
        'alias',
        'nif',
        'primer_apellido',
        'segundo_apellido',
        'telefono',
        'telefono_movil',
        'direccion',
        'codigo_postal',
        'poblacion',
        'provincia',
        'cumple_dia',
        'cumple_mes',
        'email',
        'bloqueado',
        'observaciones',
    ];
}
