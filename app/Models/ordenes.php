<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ordenes extends Model
{
    // DECIRLE QUE UTILIZARA UNA TABLA EN CONCRETO
    protected $table = 'ordenes';
    protected $fillable = [
        'id_pago',
        'id_clien',
        'nume_orde',
        'esta_orde',
    ];

    public function clientes(){
        return $this->belongsTo('App\Models\clientes', 'id_clien');
    }
}
