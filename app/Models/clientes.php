<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class clientes extends Model
{
    protected $table = 'clientes';

    protected $fillable = [
        'nomb_clien',
        'apell_clien',
        'email_clien',
        'pass_clien',
        'celu_clien',
        'tele_clien',
        'dire1_clien',
        'dire2_clien',
        'esta_clien',
    ];

    // HACER UNA RELACION DE 1 A MUCHOS
    public function ordenes(){
        return $this->hasMany('App\Models\ordenes');
    }
}
