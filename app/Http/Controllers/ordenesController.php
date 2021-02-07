<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\ordenes;

class ordenesController extends Controller
{
    public function listar(){

        $ordenes = ordenes::all()->load('clientes');

        $data = [
            'code'    => 200,
            'status'  => 'success',
            'ordenes' => $ordenes
        ];
        
        return response()->json($data, $data['code']);
    }

    public function listarPorId($id){

        $ordenes = ordenes::find($id);

        if (is_object($ordenes)) {
            $data = [
                'code'   => 200,
                'status' => 'success',
                'posts'  => $ordenes
            ];
        }else{
            $data = [
                'code'   => 404,
                'status' => 'error',
                'message'=> 'La orden no existe'
            ];
        }

        return response()->json($data, $data['code']);
    }

    public function crear(Request $request){
        // RECIBIR LOS DATOS 
        $json = $request->input('json', null);
        $params = json_decode($json);
        $params_array = json_decode($json, true);

        if(!empty($params) && !empty($params_array)){

            // VALIDAD DATOS
            $validate = \Validator::make($params_array,[
                'id_pago'      => 'required',
                'id_clien'     => 'required',
                'nume_orde'    => 'required',
            ], [
                'id_pago.required'    => 'El tipo de pago es requerido',
                'id_clien.required'   => 'El cliente es requerido',
                'nume_orde.required'  => 'El numero de orden es requerido',
            ]);

            if ($validate->fails()) {
                
                // VALIDACION HA FALLADO
                $data = [
                    'status'    => 'error',
                    'code'      => 400,
                    'mensaje'   => 'La orden no se ha creado',
                    'errors'    => $validate->errors()
                ];

            }else{
                // Guardar el producto
                $orden = new ordenes();
                $orden->id_pago   = $params->id_pago;
                $orden->id_clien  = $params->id_clien;
                $orden->nume_orde = $params->nume_orde;
                $orden->save();

                $data = [
                    'code'    => 201,
                    'status'  => 'success',
                    'post'    => $orden
                ];
            }

        }else{
            $data = [
                'code'    => 400,
                'status'  => 'error',
                'message' => 'Datos enviados incorrecto'
            ];
        }
        return response()->json($data, $data['code']);
    }

    public function update($id,Request $request){
        // RECOGER LOS DATOS POR POST
        $json = $request->input('json', null);
        $params_array = json_decode($json, true);

        if (!empty($params_array)) {
            
            // VALIDAD DATOS
            $validate = \Validator::make($params_array,[
                'id_pago'      => 'required',
                'id_clien'     => 'required',
                'nume_orde'    => 'required',
            ], [
                'id_pago.required'    => 'El tipo de pago es requerido',
                'id_clien.required'   => 'El cliente es requerido',
                'nume_orde.required'  => 'El numero de orden es requerido',
            ]);

            if ($validate->fails()) {
                
                // VALIDACION HA FALLADO
                $data = [
                    'status'    => 'error',
                    'code'      => 400,
                    'mensaje'   => 'La orden no se ha creado',
                    'errors'    => $validate->errors()
                ];

            }else{
                 // BUSCAR EL PRODUCTO A ACTUALIZAR
                $orden = ordenes::where('id', $id)
                                     ->first();

                if (!empty($orden) && is_object($orden)) {
                // ACTUALIZAR EL REGISTRO
                $orden->update($params_array);

                // DEVOLVER DATOS
                $data = [
                    'code'   => 200,
                    'status' => 'success',
                    'changes'=> $params_array
                ];
            }
        }

        }else{
            $data = [
                'code'    => 400,
                'status'  => 'error',
                'message' => 'Datos enviados incorrecto'
            ];
        }
        return response()->json($data, $data['code']);
    }

    public function delete($id){
        // BUSCAR SI EXISTE EL PRODUCTO
        $orden = ordenes::find($id);

        if ($orden) {

            // ACTUALIZAR EL CLIENTE EN LA BASE DE DATOS
            $orden->esta_orde	= '2';
            $orden->save();
            // SI EXISTE EL CLIENTE
            $data = [
                'code'    => 200,
                'status'  => 'success',
                'mensaje' => 'cliente eliminado correctamente'
            ];

        }else{

            $data =[
                'code'   => 400,
                'status' => 'error'
            ];
        }

        return response()->json($data, $data['code']);
    }
    
    public function getOrdenesByCliente($id){
        $ordenes = ordenes::where('id_clien', $id)->get();

        return response()->json([
            'status'    => 'success',
            'ordenes'   => $ordenes    
        ], 200);
    }
}
