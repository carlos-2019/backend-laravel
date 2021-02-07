<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class categorias extends Model
{
    // DECIRLE QUE UTILIZARA UNA TABLA EN CONCRETO
    protected $table = 'categorias';

    // HACER UNA RELACION DE 1 A MUCHOS
    public function productos(){
        return $this->hasMany('App\Models\productos');
    }
}
