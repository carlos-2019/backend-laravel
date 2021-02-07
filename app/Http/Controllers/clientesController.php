<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\clientes;


class clientesController extends Controller
{
    // LOGIN
    public function login(Request $request){
        $jwtAuth = new \JwtAuth();

        // RECIBIR LOS DATOS 
        $json = $request->input('json', null);
        $params = json_decode($json);
        $params_array = json_decode($json, true);

        // VALIDAR DATOS RECIBIDOS
        $validate = \Validator::make($params_array, [
            'email_clien'   => 'required|email',
            'pass_clien'    => 'required'
        ],[
            'email_clien.required' => 'El correo es requerido',
            'email_clien.email'    => 'El dato ingresado debe tener el formato de correo, example@example.com',
            'pass_clien.required'  => 'El correo es requerido'
        ]);

        if ($validate->fails()) {
            // VALIDACION HA FALLADO
            $data =[
                'code'     => 400,
                'status'   => 'error',
                'mensaje'  => 'Error al iniciar sesion',
                'errors'   => $validate->errors()

            ];
        }else{
            // VALIDACION CORRECTA
               // CIFRAR LA PASSWORD
               $pwd = hash('SHA256', $params->pass_clien); 
               // DEVOLVER TOKEN O DATOS
               $data = $jwtAuth->signup($params->email_clien, $pwd);

               if (!empty($params->gettoken)) {
               $data = $jwtAuth->signup($params->email_clien, $pwd, true);   
               }
           }
           return response()->json($data, 200);
    }
    // REGISTER
    public function register(Request $request){
        // RECIBIR LOS DATOS 
        $json = $request->input('json', null);
        $params = json_decode($json);
        $params_array = json_decode($json, true);

        if(!empty($params) && !empty($params_array)){
            // LIMPIAR DATOS PARA QUE ACEPTE CUANDO DEJE UN ESPACIO AL FINAL DE LA VARIABLE TRIM
            $params_array = array_map('trim', $params_array);
    
            // VALIDAD DATOS
            $validate = \Validator::make($params_array,[
                'nomb_clien'    => 'required|alpha',
                'apell_clien'   => 'required|alpha',
                'email_clien'   => 'required|email|unique:clientes',
                'pass_clien'    => 'required'
            ], [
                'nomb_clien.required'  => 'El nombre es requerido',
                'apell_clien.required' => 'El apellido es requerido',
                'email_clien.required' => 'El correo es requerido',
                'email_clien.email'    => 'El dato ingresado debe tener el formato de correo, example@example.com',
                'pass_clien.required'  => 'La contraseña es requerida',
            ]);

            if ($validate->fails()) {
                // VALIDACION HA FALLADO
                $data = [
                    'status'    => 'error',
                    'code'      => 400,
                    'mensaje'   => 'El usuario no se ha creado',
                    'errors'    => $validate->errors()
                ];
                
            }else{

                // VALIDACION PASADA CORRECTAMENTE

                // CIFRAR LA CONTRASEÑA (COST ES LA VECES QUE QUIERES ENCRIPTAR)
                $pwd = hash('SHA256', $params->pass_clien);    

                // CREAR EL CLIENTE
                $cliente = new clientes();
                $cliente->nomb_clien  = $params_array['nomb_clien'];
                $cliente->apell_clien = $params_array['apell_clien'];
                $cliente->email_clien = $params_array['email_clien'];
                $cliente->pass_clien  = $pwd;
                $cliente->celu_clien  = $params_array['celu_clien'];
                $cliente->tele_clien  = $params_array['tele_clien'];
                $cliente->dire1_clien = $params_array['dire1_clien'];

                // GUARDAR CLIENTE
                $cliente->save();

                $data = [
                    'status'  => 'success',
                    'code'    => 201,
                    'mensaje' => 'El cliente se ha creado correctamente',
                    'cliente' => $cliente
                ];

            }
    
            $data = [
                'code'     => 201,
                'status'   => 'success',
                'cliente'  => $params_array
            ];

        }else{
            $data = [
                'status'  => 'error',
                'code'    => 400,
                'mensaje' => 'Los datos enviados no son correctos'
            ];
        }        
        return response()->json($data,$data['code']);

    }
    // ACTUALIZAR CLIENTE
    public function update(Request $request){
        // COMPROBAR SI EL USUARIO ESTA IDENTIFICADO
        $token = $request->header('Authorization');
        $jwtAuth = new \JwtAuth();
        $checkToken = $jwtAuth->checkToken($token);
    
        // RECIBIR LOS DATOS 
        $json = $request->input('json', null);
        $params_array = json_decode($json, true);
    
        if ($checkToken && !empty($params_array)) {
            // SACAR USUARIO IDENTIFICADO
            $cliente = $jwtAuth->checkToken($token, true);

            // VALIDAD DATOS
            $validate = \Validator::make($params_array,[
            'nomb_clien'    => 'required|alpha',
            'apell_clien'   => 'required|alpha',
            'email_clien'   => 'required|email|unique:clientes',
            'pass_clien'    => 'required'
            ], [
            'nomb_clien.required'  => 'El nombre es requerido',
            'apell_clien.required' => 'El apellido es requerido',
            'email_clien.required' => 'El correo es requerido',
            'email_clien.email'    => 'El dato ingresado debe tener el formato de correo, example@example.com',
            'email_clien.unique'   => 'El correo debe ser unico',
            'pass_clien.required'  => 'La contraseña es requerida',
            ]);

            if ($validate->fails()) {

                $data = [
                    'code'    => 400,
                    'status'  => 'error',
                    'errors'  => $validate->errors(),
                ];

            }else{
            
                // QUITAR LOS CAMPOS QUE NO QUIERO ACTUALIZAR
                unset($params_array['id']);
                unset($params_array['pass_clien']);
                unset($params_array['created_at']);

            
                // ACTUALIZAR EL CLIENTE EN LA BASE DE DATOS
                $cliente_update = clientes::where('id', $cliente->id)->update($params_array);

                // devolver resultado
                $data = [
                    'status'    => 'success',
                    'code'      => 200,
                    'cliente'   => $cliente,
                    'cambios'   => $params_array
                ];

            }

        }else{
                $data = [
                    'code'      => 400,
                    'status'    => 'errors',
                    'mensaje'   => 'El usuario no esta identificado'
                ];
            }
            return response()->json($data, $data['code']);

    }
    // DETALLE CLIENTE
    public function detalle($id){
        $cliente = clientes::find($id);
        if (is_object($cliente)) {
            $data = [
                'code'      => 200,
                'status'    => 'success',
                'cliente'   => $cliente
            ];
        }else{
            $data = [
                'code'      => 404,
                'status'    => 'error',
                'mensaje'   => 'El usuario no existe'
            ]; 
        }

        return response()->json($data, $data['code']);
    }
    // ELIMINACION LOGICA
    public function delete($id, Request $request){
        // COMPROBAR SI EL USUARIO ESTA IDENTIFICADO
        $token = $request->header('Authorization');
        $jwtAuth = new \JwtAuth();
        $checkToken = $jwtAuth->checkToken($token);

        // BUSCAR SI EXISTE EL CLIENTE
        $cliente = clientes::find($id);
        if ($checkToken) {
            if(!$cliente){
                // NO EXISTE EL CLIENTE
                $data = [
                    'code'    => 404,
                    'status'  => 'error',
                    'mensaje' => 'cliente no encontrado'
                ];
            }else{
                // ACTUALIZAR EL CLIENTE EN LA BASE DE DATOS
                $cliente_update->esta_clien	= '2';
                $cliente_update->save();
                // SI EXISTE EL CLIENTE
                $data = [
                    'code'    => 200,
                    'status'  => 'success',
                    'mensaje' => 'cliente eliminado correctamente'
                ];
            }    
        }else{
            $data = [
                'code'    => 400,
                'status'  => 'error',
                'mensaje' => 'cliente no identificado'
            ];
        }
        return response()->json($data, $data['code']);
    }
    // LISTAR TODOS LOS CLIENTES
    public function listar(Request $request){

        $clientes = clientes::all();

        return response()->json([
            'code'       => 200,
            'status'     => 'success',
            'clientes' => $clientes
        ]);

        // BUSCAR TODOS LOS CLIENTES QUE TENGAN ESTADO 1
        //$clientes = clientes::all()
        //                    ->where('esta_clien', 1);
    }

}
