<?php

require_once('./db/DAO.php');
require_once('./model/Resena.php');

use dao as db;

class ResenaController
{

    /**
     * Obtiene una lista de reseñas
     *  
     *  - int $offset -> numero de resultados que dejo fuera a partir del primero [GET]
     *  - int $limit -> numero de reseñas que quieres obtener [GET]
     *  - string $key -> caracteres que el mensaje de la reseña contiene [GET]
     *  - string $order -> columna sobre la que se ordenan las reseñas [GET]
     *  - string $direction -> ASC o DESC [GET]
     * @return Resena[] Array con las reseñas
     * @author Sergio Malagon Martin
     */
    public function getResenas()
    {

        if (!isset($_GET['offset']) || !isset($_GET['limit']) || !isset($_GET['key']) || !isset($_GET['order']) || !isset($_GET['direction'])) {

            http_response_code(404);

            echo json_encode(array(

                'status' => false,

                'message' => 'Faltan parametros'

            ));

            exit();
        }

        $limit = $_GET['limit'];

        $offset = $_GET['offset'];

        $key = $_GET['key'];

        $order = $_GET['order'];

        $direction = $_GET['direction'];

        $db = new db\DAO();

        $resenas = $db->getResenas("%" . $key . "%", $order, $direction, $limit, $offset);

        foreach ($resenas as $key => &$value) {

            $user = $db->getUserById($value['usuario']);

            $pincho = $db->getPincho($value['pincho']);

            $value['puntuacion'] = $db->getLikesResena($value['id'])[0]['count'];

            $value['usuario'] = array(

                "id" => $user['id'],

                "nombre" => $user['nombre'] . " " . $user['apellidos']
            );

            $value['pincho'] = array(

                "id" => $pincho['id'],

                "nombre" => $pincho['nombre']
            );
        }

        $count = $db->getResenaCount()['count'];

        http_response_code(200);

        echo json_encode(array(

            "status" => true,

            "data" => array(

                "resenas" => $resenas,

                "count" => $count
            )
        ));
    }

    /**
     * Obtiene una reseña
     * 
     *  - int $id -> Id de la reseña [GET]
     * @return Resena El reseña obtenido
     * @author Sergio Malagon Martin
     */
    public function getResena()
    {

        if (!isset($_GET['id'])) {

            http_response_code(404);

            echo json_encode(array(

                'status' => false,

                'message' => 'Faltan parametros'

            ));

            exit();
        }

        $idResena = $_GET['id'];

        $db = new db\DAO();

        $resena = $db->getResena($idResena);

        if (is_null($resena) || !isset($resena)) {

            http_response_code(404);

            echo json_encode(array(

                "status" => false,

                "message" => "La reseña no existe"

            ));

            exit();
        }

        $user = $db->getUserById($resena['usuario']);

        $pincho = $db->getPincho($resena['pincho']);

        $resena['puntuacion'] = $db->getLikesResena($resena['id'])[0]['count'];

        $resena['usuario'] = array(

            "id" => $user['id'],

            "nombre" => $user['nombre'] . " " . $user['apellidos']
        );

        $resena['pincho'] = array(

            "id" => $pincho['id'],

            "nombre" => $pincho['nombre']
        );

        http_response_code(200);

        echo json_encode(array(

            "status" => true,

            "data" => $resena

        ));
    }

    /**
     * Actualiza los datos de una reseña
     * 
     *  - int $id -> Id de la reseña [POST]
     *  - string $mensaje -> Mensaje de la reseña [POST]
     * @return null
     * @author Sergio Malagon Martin
     */
    public function updateResena()
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

        $body_data = new Resena(json_decode($rawdata));

        if (!$body_data || !isset($body_data)) {

            http_response_code(401);

            echo json_encode(array(

                "status" => false,

                "message" => "Body not provided"

            ));

            exit();
        }


        $db = new db\DAO();

        $user_rol = $db->getUser($token_data->correo)['rol'];

        if ($user_rol === 'admin' && !is_null($body_data)) {

            if ($db->updateResena($body_data)) {

                http_response_code(201);

                echo json_encode(array(

                    "status" => true,

                    "message" => "Reseña actualizada correctamente"

                ));
            } else {

                http_response_code(400);

                echo json_encode(array(

                    "status" => false,

                    "message" => "Error actualizando la reseña"

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
     * Elimina una reseña
     * 
     *  - int $id -> Id de la reseña [POST]
     * @return null
     * @author Sergio Malagon Martin
     */
    public function deleteResena()
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

        $body_data = new Resena(json_decode($rawdata));

        if (!$body_data || !isset($body_data)) {

            http_response_code(401);

            echo json_encode(array(

                "status" => false,

                "message" => "Body not provided"

            ));

            exit();
        }


        $db = new db\DAO();

        $user_rol = $db->getUser($token_data->correo)['rol'];

        if ($user_rol === 'admin' && !is_null($body_data)) {

            if ($db->deleteResena($body_data)) {

                http_response_code(201);

                echo json_encode(array(

                    "status" => true,

                    "message" => "Reseña eliminada correctamente"

                ));
            } else {

                http_response_code(400);

                echo json_encode(array(

                    "status" => false,

                    "message" => "Error eliminando la reseña"

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
     * Inserta una reseña
     * 
     *  - int $usuario -> Id usuario [POST]
     *  - int $pincho -> Id del pincho [POST]
     *  - string $mensaje -> Mensaje de la pincho [POST]
     * @return null
     * @author Sergio Malagon Martin
     */
    public function insertResena()
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

        $body_data = new Resena(json_decode($rawdata));

        if (!$body_data || !isset($body_data)) {

            http_response_code(401);

            echo json_encode(array(

                "status" => false,

                "message" => "Body not provided"

            ));

            exit();
        }

        $db = new db\DAO();

        if ($token_data && !is_null($body_data)) {

            if ($db->insertResena($body_data)) {

                http_response_code(201);

                echo json_encode(array(

                    "status" => true,

                    "message" => "Reseña creada correctamente"

                ));
            } else {

                http_response_code(400);

                echo json_encode(array(

                    "status" => false,

                    "message" => "Error creando la reseña"

                ));
            }
        } else {

            http_response_code(401);

            echo json_encode(array(

                "status" => false,

                "message" => "Accion exclusiva para usuarios logeados"

            ));
        }
    }

    /**
     * Da like a una reseña
     * 
     * - int $id -> Id de la reseña [POST]
     * @return null
     * @author Sergio Malagon Martin
     */
    public function setLikeResena()
    {

        $auth = new Auth();

        $token_data = $auth->getDataToken();

        if (!$token_data || !isset($token_data)) {

            http_response_code(400);

            echo json_encode(array(

                "status" => false,

                "message" => "Token not provided"

            ));

            exit();
        }


        $rawdata = file_get_contents("php://input");

        $body_data = new Resena(json_decode($rawdata));

        if (!$body_data || !isset($body_data)) {

            http_response_code(401);

            echo json_encode(array(

                "status" => false,

                "message" => "Body not provided"

            ));

            exit();
        }

        $db = new db\DAO();

        $user = $db->getUserById($token_data->id);

        if (!$user['id'] === $token_data->id) {

            http_response_code(400);

            echo json_encode(array(

                'status' => false,

                'message' => 'Usuario no permitido'

            ));

            exit();
        }

        if ($db->checkIfLike($user['id'], $body_data->getId())) {

            http_response_code(400);

            echo json_encode(array(

                'status' => false,

                'message' => 'No se puede dar like a una reseña que ya lo tiene'

            ));

            exit();
        }

        if (!$db->setLikeResena($user['id'], $body_data->getId())) {

            http_response_code(400);

            echo json_encode(array(

                'status' => false,

                'message' => 'Error al likear resenña'

            ));

            exit();
        }

        http_response_code(200);

        echo json_encode(array(

            'status' => true,

            'message' => 'Reseña likeada correctamente'

        ));

        exit();
    }

    /**
     * Elimina el like de una reseña
     * 
     * - int $id -> Id de la reseña [POST]
     * @return null
     * @author Sergio Malagon Martin
     */
    public function removeLikeResena()
    {

        $auth = new Auth();

        $token_data = $auth->getDataToken();

        if (!$token_data || !isset($token_data)) {

            http_response_code(400);

            echo json_encode(array(

                "status" => false,

                "message" => "Token not provided"

            ));

            exit();
        }


        $rawdata = file_get_contents("php://input");

        $body_data = new Resena(json_decode($rawdata));

        if (!$body_data || !isset($body_data)) {

            http_response_code(401);

            echo json_encode(array(

                "status" => false,

                "message" => "Body not provided"

            ));

            exit();
        }

        $db = new db\DAO();

        $user = $db->getUserById($token_data->id);

        if (!$user['id'] === $token_data->id) {

            http_response_code(400);

            echo json_encode(array(

                'status' => false,

                'message' => 'Usuario no permitido'

            ));

            exit();
        }

        // if ($db->checkIfLike($user['id'], $body_data->getId())) {

        //     http_response_code(400);

        //     echo json_encode(array(

        //         'status' => false,

        //         'message' => 'No se puede dar like a una reseña que ya lo tiene'

        //     ));

        //     exit();
        // }

        if (!$db->removeLikeResena($user['id'], $body_data->getId())) {

            http_response_code(400);

            echo json_encode(array(

                'status' => false,

                'message' => 'Error al remove like en la resenña'

            ));

            exit();
        }

        http_response_code(200);

        echo json_encode(array(

            'status' => true,

            'message' => 'Like removed correctamente correctamente'

        ));

        exit();
    }


    /**
     * Devuelve un array con la reseñas mejor valoradas
     * 
     * @return Resena[]
     * @author Sergio Malagon Martin
     */
    public function getMoreLikedResenas()
    {

        $db = new db\DAO();

        $resenas = $db->getMoreLikedResenas();

        http_response_code(200);

        echo json_encode(array(

            'status' => true,

            'data' => $resenas

        ));

        exit();
    }
}
