<?php

require_once('./db/DAO.php');

require_once('./model/Usuario.php');

use dao as db;
use Firebase\JWT\JWT;

//  Los códigos de respuesta más comúnmente utilizados con REST son:

//  200 OK. Satisfactoria.
//  201 Created. Un resource se ha creado. Respuesta satisfactoria a un request POST o PUT.
//  400 Bad Request. El request tiene algún error, por ejemplo cuando los datos proporcionados en POST o PUT no pasan la validación.
//  401 Unauthorized. Es necesario identificarse primero.
//  404 Not Found. Esta respuesta indica que el resource requerido no se puede encontrar (La URL no se corresponde con un resource).
//  405 Method Not Allowed. El método HTTP utilizado no es soportado por este resource.
//  409 Conflict. Conflicto, por ejemplo cuando se usa un PUT request para crear el mismo resource dos veces.
//  500 Internal Server Error. Un error 500 suele ser un error inesperado en el servidor.
class UsuarioController
{
    /**
     * Comprueba si el usuario existe en la db
     * @param string $correo [Parametro POST]
     * @param string $password [Parametro POST]
     * @return string JWT Token
     * @author Sergio Malagon Martin
     */
    public function login()
    {

        $rawdata = file_get_contents("php://input");

        $body_data = new Usuario(json_decode($rawdata));

        if (!$body_data || !isset($body_data)) {

            http_response_code(401);

            echo json_encode(array(

                "status" => false,

                "message" => "Body not provided"

            ));

            exit();
        }

        $db = new db\DAO();

        if ($db->login($body_data->getCorreo(), $body_data->getPassword())) {

            $secretKey = 'pepito';

            $issuedAt = new DateTimeImmutable();

            $expire = $issuedAt->add(new DateInterval('P1D'))->getTimestamp();

            $serverName = 'localhost';

            $correo = $body_data->getCorreo();

            $user = $db->getUser($correo);

            $data = array(

                'iat' => $issuedAt->getTimestamp(),

                'iss' => $serverName,

                'nbf' => $issuedAt->getTimestamp(),

                'exp' => $expire,

                'data' => array(

                    'correo' => $correo,

                    'rol' => $user['rol'],

                    'id' => $user['id'],

                ),
            );

            $jwt = JWT::encode($data, $secretKey, 'HS512');

            http_response_code(200);

            echo json_encode(array(

                "status" => true,

                "data" => $jwt

            ));
        } else {

            http_response_code(404);

            echo json_encode(array(

                'status' => false,

                'message' => "user not found"

            ));
        }
    }


    /**
     * Inserta un usuario
     * @param string $correo [Parametro POST]
     * @param string $password [Parametro POST]
     * @param string $nombre [Parametro POST]
     * @param string $apellidos [Parametro POST]
     * @param string $img [Parametro POST] 
     * @param string $rol [Parametro POST]
     * @return null
     * @author Sergio Malagon Martin
     */
    public function insertUser()
    {

        $rawdata = file_get_contents("php://input");

        $body_data = new Usuario(json_decode($rawdata));

        if (is_null($body_data) || !isset($body_data)) {

            http_response_code(401);

            echo json_encode(array(

                "status" => false,

                "message" => "Body not provided"

            ));

            exit();
        }

        $db = new db\DAO();

        if (!preg_match("/^[^@\s]+@[^@\s]+\.[^@\s]+$/", $body_data->getCorreo()) || !preg_match("/^(?=\w*\d)(?=\w*[A-Z])(?=\w*[a-z])\S{8,}$/", $body_data->getPassword())) {

            http_response_code(400);

            echo json_encode(array(

                "status" => false,

                "message" => "Error creando el usuario, comprueba que los datos sean correctos"

            ));

            exit();
        }
        if ($db->insertUser($body_data)) {

            http_response_code(201);

            echo json_encode(array(

                "status" => true,

                "message" => "Usuario creado correctamente"

            ));

            exit();
        } else {

            http_response_code(400);

            echo json_encode(array(

                "status" => false,

                "message" => "Error creando el usuario"

            ));

            exit();
        }
    }

    /**
     * Elimina un usuario
     * @param string $correo [Parametro GET]
     * @return null
     * @author Sergio Malagon Martin
     */
    public function deleteUser()
    {

        $auth = new Auth();

        $token_data = $auth->getDataToken();

        if (!$token_data || !isset($token_data)) {

            http_response_code(401);

            echo json_encode(array(

                "status" => false,

                "message" => "Token not provided"

            ));

            exit();
        }

        $rawdata = file_get_contents("php://input");

        $body_data = new Usuario(json_decode($rawdata));

        if (is_null($body_data) || !isset($body_data)) {

            http_response_code(401);

            echo json_encode(array(

                "status" => false,

                "message" => "Body not provided"

            ));

            exit();
        }

        $db = new db\DAO();

        $user_rol = $db->getUser($token_data->correo)['rol'];

        if (($token_data->correo === $body_data->getCorreo() || $user_rol === 'admin')) {

            if ($db->deleteUser($body_data)) {

                http_response_code(200);

                echo json_encode(array(

                    "status" => true,

                    "message" => "Usuario eliminado correctamente"

                ));
            } else {

                http_response_code(400);

                echo json_encode(array(

                    "status" => false,

                    "message" => "Error eliminando el usuario"

                ));
            }
        } else {

            http_response_code(401);

            echo json_encode(array(

                "status" => false,

                "message" => "Accion exclusiva de usuarios admin"

            ));
        }
    }


    /**
     * Actualiza los datos de un usuario
     * @param string $correo [Parametro POST]
     * @param string $password [Parametro POST]
     * @param string $nombre [Parametro POST]
     * @param string $apellidos [Parametro POST]
     * @param string $img [Parametro POST]
     * @return null
     * @author Sergio Malagon Martin
     */
    public function updateUser()
    {

        $auth = new Auth();

        $token_data = $auth->getDataToken();

        if (!$token_data || !isset($token_data)) {

            http_response_code(401);

            echo json_encode(array(

                "status" => false,

                "message" => "Token not provided"

            ));

            exit();
        }


        $rawdata = file_get_contents("php://input");

        $body_data = new Usuario(json_decode($rawdata));

        if (!$body_data || !isset($body_data)) {

            http_response_code(401);

            echo json_encode(array(

                "status" => false,

                "message" => "Body not provided"

            ));

            exit();
        }

        $db = new db\DAO();

        if (($token_data->correo === $body_data->getCorreo())) {

            if ($db->updateUser($body_data)) {

                http_response_code(201);

                echo json_encode(array(

                    "status" => true,

                    "message" => "Usuario actualizado correctamente"

                ));
            } else {

                http_response_code(400);

                echo json_encode(array(

                    "status" => false,

                    "message" => "Error actualizando el usuario"

                ));
            }
        } else {

            http_response_code(401);

            echo json_encode(array(

                "status" => false,

                "message" => "Accion exclusiva de usuarios admin"

            ));
        }
    }

    public function updateUserImg()
    {

        if (!isset($_GET['id'])) {

            http_response_code(404);

            echo json_encode(array(

                'status' => false,

                'message' => 'Faltan parametros'

            ));

            exit();
        }

        $userID = $_GET['id'];

        if (!file_exists("img/img_usuarios")) {

            mkdir("img/img_usuarios");
        }

        $target_dir = "img/img_usuarios/$userID/";

        if (!file_exists($target_dir)) {

            mkdir($target_dir);
        }

        $db = new db\DAO();

        $target_file = $target_dir . basename($_FILES["file"]["name"]);

        if (!file_exists($target_file)) {

            move_uploaded_file($_FILES["file"]["tmp_name"], $target_file);
        }
        if (!$db->updateUserImg($userID, $_FILES["file"]["name"])) {

            http_response_code(400);

            echo json_encode(array(

                "status" => false,

                "message" => 'Error al actualizar la imagen'
            ));

            exit();
        }


        http_response_code(200);

        echo json_encode(array(

            "status" => true,

            "message" => 'Imagen actualizada correctamente'
        ));

        exit();
    }

    /**
     * Obtiene una lista de usuarios
     * @param int $offset [Parametro GET]
     * @param int $limit [Parametro GET]
     * @return Usuario[] Los usuarios obtenidos
     * @author Sergio Malagon Martin
     */
    public function getUsers()
    {

        $auth = new Auth();

        $token_data = $auth->getDataToken();

        if (!$token_data || !isset($token_data)) {

            http_response_code(401);

            echo json_encode(array(

                "status" => false,

                "message" => "Token not provided"

            ));

            exit();
        }

        if (!isset($_GET['offset']) || !isset($_GET['limit']) || !isset($_GET['key']) || !isset($_GET['order']) || !isset($_GET['direction'])) {

            http_response_code(404);

            echo json_encode(array(

                'status' => false,

                'message' => 'Faltan parametros'

            ));

            exit();
        }

        $offset = $_GET['offset'];

        $limit = $_GET['limit'];

        $key = $_GET['key'];

        $order = $_GET['order'];

        $direction = $_GET['direction'];

        $db = new db\DAO();

        $user_rol = $db->getUser($token_data->correo)['rol'];

        if ($user_rol !== 'admin') {

            http_response_code(401);

            echo json_encode(array(

                "status" => false,

                "message" => "Accion exclusiva de usuarios admin"

            ));

            exit();
        }

        $users = $db->getUsers("%" . $key . "%", $order, $direction, $limit, $offset);

        $count = $db->getUsersCount()['count'];

        http_response_code(200);

        echo json_encode(array(

            "status" => true,

            "data" => array(

                "users" => $users,

                "count" => $count

            )

        ));
    }

    /**
     * Obtiene un usuario
     * @param string $correo [Parametro GET]
     * @return Usuario El usuario obtenido
     * @author Sergio Malagon Martin
     */
    public function getUser()
    {

        if (!isset($_GET['correo'])) {

            http_response_code(404);

            echo json_encode(array(

                'status' => false,

                'message' => 'Faltan parametros'

            ));

            exit();
        }

        $correoUser = $_GET['correo'];

        $db = new db\DAO();

        $user = $db->getUser($correoUser);

        if (is_null($user) || !isset($user)) {

            http_response_code(404);

            echo json_encode(array(

                "status" => false,

                "message" => "El usuario no existe"

            ));
        } else {

            http_response_code(200);

            echo json_encode(array(

                "status" => true,

                "data" => $user

            ));
        }
    }
}
