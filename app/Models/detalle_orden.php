<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class detalle_orden extends Model
{
    protected $table = 'detalle_orden';

    public function orden(){
        return $this->belongsTo('App\Models\ordenes', 'id_orde');
    }

    public function producto(){
        return $this->belongsTo('App\Models\productos', 'id_prod');
    }
}
