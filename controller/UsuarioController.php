<?php

require_once('./db/DAO.php');

use dao as db;
use Firebase\JWT\JWT;

class UsuarioController
{

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
    public function login()
    {

        $rawdata = file_get_contents("php://input");

        $datos = json_decode($rawdata);

        $db = new db\DAO();

        if ($db->login($datos->email, $datos->password)) {

            $secretKey = 'pepito';

            $issuedAt = new DateTimeImmutable();

            $expire = $issuedAt->add(new DateInterval('P1D'))->getTimestamp();

            $serverName = 'localhost';

            $email = $datos->email;

            $rol = $db->getUser($datos->email)[0]['rol'];

            $data = array(

                'iat' => $issuedAt->getTimestamp(),

                'iss' => $serverName,

                'nbf' => $issuedAt->getTimestamp(),

                'exp' => $expire,

                'data' => array(

                    'email' => $email,

                    'rol' => $rol

                ),
            );

            $jwt = JWT::encode($data, $secretKey, 'HS512');

            http_response_code(200);

            echo json_encode(array(

                "status" => true,

                "data" => $jwt

            ));
        } else {

            http_response_code(400);

            echo json_encode(array(

                'status' => false,

                'message' => "user not found"

            ));
        }
    }

}
