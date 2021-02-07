<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\productos;

class productosController extends Controller
{
    public function listar(){

        $productos = productos::all()->load('categorias','stand');

        return response()->json([
            'code'       => 200,
            'status'     => 'success',
            'productos'  => $productos
        ]);
    }

    public function listarPorId($id){

        $producto = productos::find($id);

        if (!$producto) {
            $data = [
                'code'      => 404,
                'status'    => 'error',
                'mensaje'   => 'producto no encontrado'
            ];
        }else{
            $producto->load('categorias','stand');
            $data = [
                'code'      => 200,
                'status'    => 'success',
                'productos' => $producto
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
                'id_categ'      => 'required',
                'id_stand'      => 'required',
                'nomb_prod'     => 'required',
                'stock_prod'    => 'required',
                'puni_prod'     => 'required',
                'color'         => 'required',
                'talla'         => 'required',
                'material'      => 'required',
            ], [
                'id_categ.required'   => 'El id_categ es requerido',
                'id_stand.required'   => 'El id_stand es requerido',
                'nomb_prod.required'  => 'El nombre del producto es requerido',
                'stock_prod.required' => 'El stock del producto es requerido',
                'puni_prod.required'  => 'El precio unitario del producto es requerido',
                'color.required'      => 'El color del producto es requerido',
                'talla.required'      => 'La talla del producto es requerido',
                'material.required'   => 'El material del producto es requerido',
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
                // Guardar el producto
                $producto = new productos();
                $producto->id_categ = $params->id_categ;
                $producto->id_stand = $params->id_stand;
                $producto->nomb_prod = $params->nomb_prod;
                $producto->desc_prod = $params->desc_prod;
                $producto->stock_prod = $params->stock_prod;
                $producto->puni_prod = $params->puni_prod;
                $producto->color = $params->color;
                $producto->talla = $params->talla;
                $producto->material = $params->material;
                $producto->descu_prod = $params->descu_prod;
                $producto->save();

                $data = [
                    'code'    => 201,
                    'status'  => 'success',
                    'producto'    => $producto
                ];
            }
        }else{
            $data = [
                'code'    => 400,
                'status'  => 'error',
                'message' => 'Envia los datos correctamente'
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
                'id_categ'      => 'required',
                'id_stand'      => 'required',
                'nomb_prod'     => 'required',
                'stock_prod'    => 'required',
                'puni_prod'     => 'required',
                'color'         => 'required',
                'talla'         => 'required',
                'material'      => 'required',
            ], [
                'id_categ.required'   => 'El id_categ es requerido',
                'id_stand.required'   => 'El id_stand es requerido',
                'nomb_prod.required'  => 'El nombre del producto es requerido',
                'stock_prod.required' => 'El stock del producto es requerido',
                'puni_prod.required'  => 'El precio unitario del producto es requerido',
                'color.required'      => 'El color del producto es requerido',
                'talla.required'      => 'La talla del producto es requerido',
                'material.required'   => 'El material del producto es requerido',
            ]);

            if ($validate->fails()) {

                $data = [
                    'code'    => 400,
                    'status'  => 'error',
                    'errors'  => $validate->errors(),
                ];

            }else{
                // BUSCAR EL PRODUCTO A ACTUALIZAR
                $producto = productos::where('id', $id)
                                 ->first();

                if (!empty($producto) && is_object($producto)) {
                // ACTUALIZAR EL REGISTRO
                $producto->update($params_array);

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
        $producto = productos::find($id);

        if ($producto) {

            // ACTUALIZAR EL CLIENTE EN LA BASE DE DATOS
            $producto->esta_prod	= '2';
            $producto->save();
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

    public function getProductoByCategory($id){
        $producto = productos::where('id_categ', $id)->get();

        return response()->json([
            'status'    => 'success',
            'producto'  => $producto    
        ], 200);
    }

    public function buscarProducto(Request $request){

        if ($request->has('buscarProducto')) {
            $producto = productos::where('nomb_prod', 'Like', '%' . $request->buscarProducto .'%')
                        ->get()
                        ->load('categorias','stand');
        }else{
            $producto = productos::all()->load('categorias','stand');
        }
        return $producto;
    }
}
