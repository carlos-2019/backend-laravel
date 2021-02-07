<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class stand extends Model
{
    // DECIRLE QUE UTILIZARA UNA TABLA EN CONCRETO
    protected $table = 'stand';

    protected $fillable = [
        'nomb_stand',
        'nume_stand',
        'celu_stand',
        'tele_stand',
        'mail_stand',
    ];
}
