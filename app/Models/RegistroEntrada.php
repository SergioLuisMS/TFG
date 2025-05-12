<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistroEntrada extends Model
{
    use HasFactory;

    protected $fillable = [
        'empleado_id',
        'fecha',
        'hora_real_entrada',
    ];

    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }
}
