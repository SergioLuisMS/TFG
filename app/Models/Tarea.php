<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tarea extends Model
{
    protected $fillable = [
        'orden_id',
        'empleado_id',
        'fecha_inicio',
        'fecha_fin',
        'descripcion',
        'tiempo_previsto',
        'estado',
        'cronometro_inicio',
        'tiempo_real'
    ];


    public function comentarios()
    {
        return $this->hasMany(Comentario::class);
    }



    public function orden()
    {
        return $this->belongsTo(Orden::class);
    }


    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }
}
