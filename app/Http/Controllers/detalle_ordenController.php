<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\detalle_orden;

class detalle_ordenController extends Controller
{
    public function getDetalleOrdenByOrden($id){

        $detalleOrden = detalle_orden::find($id);

        if ($detalleOrden) {
            
            $detalleOrden->load('orden','producto');
            $data = [
                'code'          => 200,
                'status'        => 'success',
                'detalleOrden'  => $detalleOrden
            ];
        }else{
            $data = [
                'code'          => 404,
                'status'        => 'error'
            ];
        }
        return response()->json($data, $data['code']);
    }
}
