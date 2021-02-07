<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\categorias;

class categoriasController extends Controller
{
    public function listar(Request $request){

        $categorias = categorias::all();

        $data = [
            'code'       => 200,
            'status'     => 'success',
            'categorias' => $categorias
        ];

        return response()->json($data, $data['code']);
    }
}
