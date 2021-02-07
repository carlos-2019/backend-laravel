<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class productos extends Model
{
    // DECIRLE QUE UTILIZARA UNA TABLA EN CONCRETO
    protected $table = 'productos';
    protected $fillable = [
        'id_categ',
        'id_stand',
        'nomb_prod',
        'desc_prod',
        'stock_prod',
        'puni_prod',
        'color',
        'tall',
        'material',
        'descu_prod',
        'esta_prod',
    ];

    public function categorias(){
        return $this->belongsTo('App\Models\categorias', 'id_categ');
    }

    public function stand(){
        return $this->belongsTo('App\Models\stand', 'id_stand');
    }
}
