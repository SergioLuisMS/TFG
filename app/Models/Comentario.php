<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comentario extends Model
{
    protected $fillable = ['tarea_id', 'empleado_id', 'contenido', 'valoracion'];

    public function tarea()
    {
        return $this->belongsTo(Tarea::class);
    }

    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }
}
