<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\stand;
use Illuminate\Support\Facades\Http;

class standController extends Controller
{

    public function listar(){

        $stand = stand::paginate(5);

        return response()->json([
            'code'       => 200,
            'status'     => 'success',
            'stand'      => $stand
        ]);
    }

    public function listarPorId($id){

        $stand = stand::find($id);

        $data = [
            'code'   => 200,
            'status' => 'success',
            'stand'  => $stand
        ];
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
                'nomb_stand'      => 'required',
                'nume_stand'      => 'required',
                'mail_stand'      => 'required',
            ], [
                'nomb_stand.required'   => 'El nombre del stand es requerido',
                'nume_stand.required'   => 'El numero del stand es requerido',
                'mail_stand.required'   => 'El correo del stand es requerido',
            ]);
            
            if ($validate->fails()) {
                
                // VALIDACION HA FALLADO
                $data = [
                    'status'    => 'error',
                    'code'      => 400,
                    'mensaje'   => 'El stand no se ha creado',
                    'errors'    => $validate->errors()
                ];

            }else{
                // Guardar el producto
                $stand = new stand();
                $stand->nomb_stand = $params->nomb_stand;
                $stand->nume_stand = $params->nume_stand;
                $stand->celu_stand = $params->celu_stand;
                $stand->tele_stand = $params->tele_stand;
                $stand->mail_stand = $params->mail_stand;
                $stand->save();

                $data = [
                    'code'    => 201,
                    'status'  => 'success',
                    'stand'    => $stand
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

    public function update($id, Request $request){
        
        // RECOGER LOS DATOS POR POST
        $json = $request->input('json', null);
        $params_array = json_decode($json, true);

        if (!empty($params_array)) {

            // VALIDAD DATOS
            $validate = \Validator::make($params_array,[
                'nomb_stand'      => 'required',
                'nume_stand'      => 'required',
                'mail_stand'      => 'required',
            ], [
                'nomb_stand.required'   => 'El nombre del stand es requerido',
                'nume_stand.required'   => 'El numero del stand es requerido',
                'mail_stand.required'   => 'El correo del stand es requerido',
            ]);

            if ($validate->fails()) {

                $data = [
                    'code'    => 400,
                    'status'  => 'error',
                    'errors'  => $validate->errors(),
                ];

            }else{
                // BUSCAR EL STAND A ACTUALIZAR
                $stand = stand::where('id', $id)
                                 ->first();

                if (!empty($stand) && is_object($stand)) {
                // ACTUALIZAR EL REGISTRO
                $stand->update($params_array);

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

    public function delete($id, Request $request){

        // BUSCAR SI EXISTE EL PRODUCTO
        $stand = stand::find($id);

        if ($stand) {

            // ACTUALIZAR EL CLIENTE EN LA BASE DE DATOS
            $stand->esta_stand	= '2';
            $stand->save();
            // SI EXISTE EL CLIENTE
            $data = [
                'code'    => 200,
                'status'  => 'success',
                'mensaje' => 'stand eliminado correctamente'
            ];

        }else{
            $data =[
                'code'   => 400,
                'status' => 'error'
            ];
        }

        return response()->json($data, $data['code']);
    }
    
    public function buscarStand(Request $request){

        if ($request->has('buscarStand')) {
            $stand = stand::where('nomb_stand', 'Like', '%' . $request->buscarStand .'%')
                        ->orWhere('nume_stand', 'Like', '%' . $request->buscarStand .'%')
                        ->get();
        }else{
            $stand = stand::all();
        }
        return $stand;
    }

}
