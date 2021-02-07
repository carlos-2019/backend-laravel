<?php
namespace App\Helpers;

use Firebase\JWT\JWT;
use Illuminate\Support\Facades\DB;
use App\Models\clientes;

class JwtAuth {

    public $key;

    public function __construct(){
        $this->key = 'esto_es_una_clave_super_secreta-19992321241';
    }

    public function signup($email, $password, $getToken = null){
        // BUSCAR SI EXISTE EL USUARIO CON SUS CREDENCIALES
        $cliente = clientes::where([
            'email_clien' => $email,
            'pass_clien' => $password
        ])->first();
        // COMPROBAR SI SON CORRECTAS(OBJETO)
        $signup = false;
        if (is_object($cliente)) {
            $signup = true;
        }
        // GENERAR EL TOKEN CON LOS DATOS DEL USUARIO IDENTIFICADOS
        if ($signup) {

            $token = array(
                'id'           => $cliente->id,
                'nomb_clien'   => $cliente->nomb_clien,
                'apell_clien'  => $cliente->apell_clien,
                'email_clien'  => $cliente->email_clien,
                'celu_clien'   => $cliente->celu_clien,
                'tele_clien'   => $cliente->tele_clien,
                'dire1_clien'  => $cliente->dire1_clien,
                'esta_clien'   => $cliente->esta_clien,
                'iat'     => time(),
                // 7 DIAS 24 HORAS 60 MINUTOS 60 SEGUNDOS
                'exp'     => time() + (8*60*60)
            );

            $jwt = JWT::encode($token, $this->key, 'HS256');
            $decoded = JWT::decode($jwt, $this->key, ['HS256']);

            // DEVOLVER LOS DATOS DECODIFICADOS O EL TOKEN, EN FUNCION DE UN PARAMETRO
            if (is_null($getToken)) {
                $data = [
                    $jwt
                ];
            }else{
                $data = $decoded;
            }

        }else{
            $data = array(
                'status' => 'error',
                'mensaje' => 'Login incorrecto'
            );
        }
        return $data;
    }

    public function checkToken($jwt, $getIdentity = false){
        $auth = false;

        try {
            $jwt = str_replace('"','', $jwt);
            $decoded = JWT::decode($jwt, $this->key, ['HS256']);
        // ERRORES CLASICOS
        } catch (\UnexpectedValueException $e) {
            $auth = false;
        } catch(\DomainException $e){
            $auth = false;
        }
        if (!empty($decoded) && is_object($decoded) && isset($decoded->id)) {
            $auth = true;
        }else{
            $auth = false;
        }

        if ($getIdentity) {
            return $decoded;
        }
        return $auth;
    }

}