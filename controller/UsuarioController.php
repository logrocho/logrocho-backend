<?php

require_once('./db/DB.php');

use database as db;
use Firebase\JWT\JWT;

class UsuarioController
{

    private $db;

    /**
     Los códigos de respuesta más comúnmente utilizados con REST son:
     
     200 OK. Satisfactoria.
     201 Created. Un resource se ha creado. Respuesta satisfactoria a un request POST o PUT.
     400 Bad Request. El request tiene algún error, por ejemplo cuando los datos proporcionados en POST o PUT no pasan la validación.
     401 Unauthorized. Es necesario identificarse primero.
     404 Not Found. Esta respuesta indica que el resource requerido no se puede encontrar (La URL no se corresponde con un resource).
     405 Method Not Allowed. El método HTTP utilizado no es soportado por este resource.
     409 Conflict. Conflicto, por ejemplo cuando se usa un PUT request para crear el mismo resource dos veces.
     500 Internal Server Error. Un error 500 suele ser un error inesperado en el servidor.
     
     **/

    /**
     * Get header Authorization
     * */
    private function getAuthorizationHeader()
    {
        $headers = null;
        if (isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER["Authorization"]);
        } else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        } elseif (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();
            // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
            //print_r($requestHeaders);
            if (isset($requestHeaders['Authorization'])) {
                $headers = trim($requestHeaders['Authorization']);
            }
        }
        return $headers;
    }


    /**
     * Get access token from header
     * */
    private function getBearerToken()
    {
        $headers = $this->getAuthorizationHeader();

        if (!empty($headers)) {

            if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {

                $jwt = $matches[1];

                if ($jwt) {

                    return $jwt;
                } else {

                    header('HTTP/1.0 400 Bad Request');

                    echo json_encode(array(

                        'result' => false,


                        'message' => "jwt not found"
                    ));

                    exit;
                }
            }
        }

        header('HTTP/1.0 400 Bad Request');

        echo json_encode(array(

            'result' => false,

            'message' => "jwt not found"

        ));

        exit;
    }


    public function validateToken()
    {

        try {

            $secretKey = 'pepito';

            $jwt = $this->getBearerToken();

            $token = JWT::decode($jwt, $secretKey, ['HS512']);

            $now = new DateTimeImmutable();

            if ($token->nbf > $now->getTimestamp() || $token->exp < $now->getTimestamp()) {

                header('HTTP/1.0 400 Bad Request');

                echo json_encode(array(

                    'result' => false,

                    'message' => "jwt not valid"

                ));
            } else {

                $this->db = new db\DB();

                if ($this->db->userExist($token->data->email)) {

                    echo json_encode(array(

                        'result' => true

                    ));
                } else {

                    header('HTTP/1.0 400 Bad Request');

                    echo json_encode(array(

                        'result' => false,

                        'message' => "jwt not valid"

                    ));
                }
            }
        } catch (\Exception $th) {

            header('HTTP/1.0 400 Bad Request');

            echo json_encode(array(

                'result' => false,

                'message' => "jwt not valid"

            ));
        }
    }

    public function login()
    {
        $rawdata = file_get_contents("php://input");

        $datos = json_decode($rawdata, true);

        $this->db = new db\DB();

        if ($this->db->login($datos['correo'], $datos['password'])) {

            $secretKey = 'pepito';

            $issuedAt = new DateTimeImmutable();

            $expire = $issuedAt->add(new DateInterval('P2D'))->getTimestamp();

            $serverName = 'localhost';

            $username = $datos['correo'];

            $data = array(

                'iat' => $issuedAt->getTimestamp(),

                'iss' => $serverName,

                'nbf' => $issuedAt->getTimestamp(),

                'exp' => $expire,

                'data' => array(

                    'email' => $username

                ),
            );

            $jwt = JWT::encode($data, $secretKey, 'HS512');

            echo json_encode(array(

                "result" => true,

                "token" => $jwt

            ));
        } else {

            header('HTTP/1.0 400 Bad Request');

            echo json_encode(array(

                'result' => false,

                'message' => "user not found"

            ));

        }
    }



    // /**
    //  * Autenticación básica. Se obtendrá un response si el usuario y password facilitado en 
    //  * la cabecera es correcta, sin devolver ningún token adicional.
    //  */
    // public function getCategoriasNoToken()
    // {

    //     echo json_encode(array(
    //         "resultado" => true
    //     ));

    //     return;


    //     $check = $this->basicAuthorization();
    //     if ($check) {
    //         header("Content-Type: application/json', 'HTTP/1.1 200 OK");
    //         $this->db = new repo\BD();
    //         $categorias = $this->db->getCategorias();
    //         echo json_encode($categorias);
    //     } else {
    //         header('HTTP/1.1 401 Unauthorized', true, 401);
    //         echo "Acceso no autorizado ";
    //     }
    // }

    // /**
    //  * Autenticación básica. Se obtendrá un response si el usuario y password facilitado en 
    //  * la cabecera es correcta, pero en esta ocasión devolverá un token adicional (JWT), que será transportado
    //  * en el resto de peticiones mediante autenticación Bearer.
    //  */
    // public function getTokenJWT()
    // {
    //     $check = $this->basicAuthorization();
    //     if ($check) {
    //         header("Content-Type: application/json', 'HTTP/1.1 200 OK");
    //         $array = array();
    //         $array["token"] = $this->getJWT($this->correo, $this->passwd);
    //         $array["correo"] = $this->correo;
    //         // Lo suyo sería obtener el token con datos del usuario al que se le entrega el token, si es que
    //         // hay usuarios personalizados. Si es un servicio general, se le entrega un dato genérico para todos
    //         // Se transporta el token para ser reenviado en posteriores llamadas al API Rest
    //         echo json_encode($array);
    //     } else {
    //         header('HTTP/1.1 401 Unauthorized', true, 401);
    //         echo "Acceso no autorizado ";
    //     }
    // }


    // /**
    //  * Autenticación básica. Se obtendrá un response si el usuario y password facilitado en
    //  * la cabecera es correcta, devolverá un token simple que será transportado
    //  * en el resto de peticiones mediante autenticación Bearer.
    //  */
    // public function getTokenSimple()
    // {
    //     $check = $this->basicAuthorization();
    //     if ($check) {
    //         header("Content-Type: application/json', 'HTTP/1.1 200 OK");
    //         $array = array();
    //         $array["token"] = $this->getToken(20);
    //         echo json_encode($array);
    //     } else {
    //         header('HTTP/1.1 401 Unauthorized', true, 401);
    //         echo "Acceso no autorizado ";
    //     }
    // }


    // /**
    //  * Autenticación Bearer. Se transporta en la cabecera el token simple obtenido en la petición de login simple.
    //  * Debe verificarse que el token es válido: se almacenará en BD la lista de tokens para ello.
    //  */
    // public function getCategoriasSimple()
    // {
    //     $token = $this->getBearerToken();
    //     // Este condicional debe transformarse en una peticion a BD
    //     // y verificar que el $token existe en la tabla de la BD
    //     if ($token == "da25c57ab6a20f3f03c3") {
    //         header("Content-Type: application/json', 'HTTP/1.1 200 OK");
    //         $array = array();
    //         $this->db = new repo\BD();
    //         $categorias = $this->db->getCategorias();
    //         echo json_encode($categorias);
    //     } else {
    //         header('HTTP/1.1 401 Unauthorized', true, 401);
    //         echo "Acceso no autorizado ";
    //     }
    // }

    // public function getProductosSimple($pk)
    // {
    //     $token = $this->getBearerToken();
    //     // Este condicional debe transformarse en una peticion a BD
    //     // y verificar que el $token existe en la tabla de la BD
    //     if ($token == "19a36dbc7138d34b2efe") {
    //         header("Content-Type: application/json', 'HTTP/1.1 200 OK");
    //         $array = array();
    //         $this->db = new repo\BD();
    //         $productos = $this->db->getProductos($pk);
    //         echo json_encode($productos);
    //     } else {
    //         header('HTTP/1.1 401 Unauthorized', true, 401);
    //         echo "Acceso no autorizado ";
    //     }
    // }

    // public function getPedidosSimple($pk)
    // {
    //     $token = $this->getBearerToken();
    //     // Este condicional debe transformarse en una peticion a BD
    //     // y verificar que el $token existe en la tabla de la BD
    //     if ($token == "8443e2c3ef710453a586") {
    //         header("Content-Type: application/json', 'HTTP/1.1 200 OK");
    //         $array = array();
    //         $this->db = new repo\BD();
    //         $pedidos = $this->db->getDetallePedidos($pk);
    //         echo json_encode($pedidos);
    //     } else {
    //         header('HTTP/1.1 401 Unauthorized', true, 401);
    //         echo "Acceso no autorizado ";
    //     }
    // }

    // /**
    //  * Autenticación Bearer. Se transporta en la cabecera el token JWT obtenido en la petición de login.
    //  * Debe verificarse que el JWT es válido. Puede usarse a modo de testeo adicional https://jwt.io/
    //  */
    // public function getCategoriasJWT()
    // {
    //     $jwt = $this->getBearerToken();
    //     try {
    //         $dataObject = JWT::decode($jwt, $this->key, array('HS256'));
    //         $data = (array) $dataObject;
    //         if ($data["passwordToken"] = self::DATA) {
    //             header("Content-Type: application/json', 'HTTP/1.1 200 OK");
    //             $array = array();
    //             $this->db = new repo\BD();
    //             $categorias = $this->db->getCategorias();
    //             echo json_encode($categorias);
    //         } else {
    //             header('HTTP/1.1 401 Unauthorized', true, 401);
    //             echo "Token incorrecto ";
    //         }
    //     } catch (\Exception $e) {
    //         header('HTTP/1.1 401 Unauthorized', true, 401);
    //         echo "Acceso no autorizado. Token inválido. " . $e->getMessage();
    //     }
    // }

    // public function getProductosJWT($pk)
    // {
    //     $jwt = $this->getBearerToken();
    //     try {
    //         $dataObject = JWT::decode($jwt, $this->key, array('HS256'));
    //         $data = (array) $dataObject;
    //         if ($data["passwordToken"] = self::DATA) {
    //             header("Content-Type: application/json', 'HTTP/1.1 200 OK");
    //             $array = array();
    //             $this->db = new repo\BD();
    //             $productos = $this->db->getProductos($pk);
    //             echo json_encode($productos);
    //         } else {
    //             header('HTTP/1.1 401 Unauthorized', true, 401);
    //             echo "Token incorrecto ";
    //         }
    //     } catch (\Exception $e) {
    //         header('HTTP/1.1 401 Unauthorized', true, 401);
    //         echo "Acceso no autorizado. Token inválido. " . $e->getMessage();
    //     }
    // }

    // public function getPedidosJWT($pk)
    // {
    //     $jwt = $this->getBearerToken();
    //     try {
    //         $dataObject = JWT::decode($jwt, $this->key, array('HS256'));
    //         $data = (array) $dataObject;
    //         if ($data["passwordToken"] = self::DATA) {
    //             header("Content-Type: application/json', 'HTTP/1.1 200 OK");
    //             $array = array();
    //             $this->db = new repo\BD();
    //             $pedidos = $this->db->getDetallePedidos($pk);
    //             echo json_encode($pedidos);
    //         } else {
    //             header('HTTP/1.1 401 Unauthorized', true, 401);
    //             echo "Token incorrecto ";
    //         }
    //     } catch (\Exception $e) {
    //         header('HTTP/1.1 401 Unauthorized', true, 401);
    //         echo "Acceso no autorizado. Token inválido. " . $e->getMessage();
    //     }
    // }

    // /**
    //  * Body
    //  */

    // function registrarUsuario()
    // {
    //     $rawdata = file_get_contents("php://input");
    //     $datos = json_decode($rawdata, true);

    //     $this->db = new repo\BD();

    //     $email = $datos["email"];
    //     $passw = $datos ["passw"];
    //     $direccion = $datos["direccion"];
    //     header("Content-Type: application/json', 'HTTP/1.1 200 OK");
    //     if (empty($email) || empty($passw) || empty($direccion)) {
    //         $array = array(
    //             "errorMessage" => "Error, el usuario no ha sido creado correctamente"
    //         );
    //         echo json_encode($array);
    //         return;
    //     } else  if ($this->db->registrar($email, $passw, $direccion)) {
    //         $array = array(
    //             "email" => $email,
    //             "passw" => $passw
    //         );
    //         echo json_encode($array);
    //         return;
    //     }
    // }

    // /**
    //  * Get header Authorization
    //  * */
    // function getAuthorizationHeader()
    // {
    //     $headers = null;
    //     if (isset($_SERVER['Authorization'])) {
    //         $headers = trim($_SERVER["Authorization"]);
    //     } else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
    //         $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
    //     } elseif (function_exists('apache_request_headers')) {
    //         $requestHeaders = apache_request_headers();
    //         // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
    //         $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
    //         //print_r($requestHeaders);
    //         if (isset($requestHeaders['Authorization'])) {
    //             $headers = trim($requestHeaders['Authorization']);
    //         }
    //     }
    //     return $headers;
    // }


    // /**
    //  * get access token from header
    //  * */
    // private function getBearerToken()
    // {
    //     $headers = $this->getAuthorizationHeader();
    //     // HEADER: Get the access token from the header
    //     if (!empty($headers)) {
    //         if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
    //             return $matches[1];
    //         }
    //     }
    //     return null;
    // }


    // private function basicAuthorization()
    // {
    //     if ((isset($_SERVER['PHP_AUTH_USER'])) && (isset($_SERVER['PHP_AUTH_PW']))) {
    //         $correo = $_SERVER["PHP_AUTH_USER"];
    //         $passw = $_SERVER['PHP_AUTH_PW'];
    //         $this->db = new repo\BD();

    //         $resultado = $this->db->getRestaurante($correo, $passw);
    //         if ($resultado) {
    //             foreach ($resultado as $key) {
    //                 $this->correo = $key["Correo"];
    //                 $this->passwd = $key["Clave"];
    //             }
    //             return true;
    //         }

    //         // if (($_SERVER['PHP_AUTH_USER'] == "correo@correo.com") && ($_SERVER['PHP_AUTH_PW'] == "admin")) {
    //         //     return true;
    //         // } else {
    //         //     return false;
    //         // }
    //     } else {
    //         return false;
    //     }
    // }

    // private function tokenAuthorization()
    // {
    //     if (($_SERVER['PHP_AUTH_USER'] == "correo@correo.com") && ($_SERVER['PHP_AUTH_PW'] == "admin")) {
    //         return true;
    //     } else {
    //         return false;
    //     }
    // }

    // // Token criptográficamente seguro, pero no podemos asociarle informacion como sucede con el JWT
    // private function getToken($longitud)
    // {
    //     if ($longitud < 4) {
    //         $longitud = 4;
    //     }
    //     $token = bin2hex(random_bytes(($longitud - ($longitud % 2)) / 2));
    //     // El token sólo será válido si no está repetido en BD. Se ser así, se 
    //     // almacenará en la tabla correspondiente
    //     return $token;
    // }

    // // Ejecutar composer require firebase/php-jwt en la raíz de mvc_api_rest
    // // Esto generará la carpeta vendor con la librería de Firebase para obtener tokens
    // // En el frontal index.php se incluye 'require("./vendor/autoload.php");'
    // // En esta clase se incluye 'use Firebase\JWT\JWT;'
    // // URL tutorial: https://anexsoft.com/implementacion-de-json-web-token-con-php

    // private function getJWT($data)
    // {
    //     $time = time();
    //     $token = array(
    //         'iat' => $time, // Tiempo que inició el token
    //         'exp' => $time + (60 * 60), // Tiempo que expirará el token (+1 hora)
    //         'data' => [ // información del usuario o lo que consideremos necesario incluir
    //             'idUser' => 1, // Se obtendría de BD si hay usuarios personalizados
    //             'passwordUser' => 'admin', // Se obtendría de BD si hay usuarios personalizados
    //             'passwordToken' => $data // Para usuarios genéricos
    //         ]
    //     );
    //     $jwt = JWT::encode($token, $this->key);
    //     return $jwt;
    //     //$data = JWT::decode($jwt, $key, array('HS256')); // Si ha expirado dará un error
    //     //var_dump($jwt);
    //     //var_dump($data);
    // }


    // // private function getJWT($correo, $passw)
    // // {
    // //     $time = time();
    // //     $token = array(
    // //         'iat' => $time, // Tiempo que inició el token
    // //         'exp' => $time + (60 * 60), // Tiempo que expirará el token (+1 hora)
    // //         'data' => [ // información del usuario o lo que consideremos necesario incluir
    // //             'idUser' => $correo, // Se obtendría de BD si hay usuarios personalizados
    // //             'passwordUser' => $passw, // Se obtendría de BD si hay usuarios personalizados
    // //         ]
    // //     );
    // //     $jwt = JWT::encode($token, $this->key);
    // //     return $jwt;
    // //     //$data = JWT::decode($jwt, $key, array('HS256')); // Si ha expirado dará un error
    // //     //var_dump($jwt);
    // //     //var_dump($data);
    // // }
}
