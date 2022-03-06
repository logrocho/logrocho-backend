<?php

require_once('./db/DAO.php');
require_once('./model/Pincho.php');

use dao as db;

class PinchoController
{

    /**
     * Obtiene una lista de pinchos
     *  
     *  - int $offset -> numero de resultados que dejo fuera a partir del primero [GET]
     *  - int $limit -> numero de pinchos que quieres obtener [GET]
     *  - string $key -> caracteres que el nombre del pincho contiene [GET]
     *  - string $order -> columna sobre la que se ordenan los pinchos [GET]
     *  - string $direction -> ASC o DESC [GET]
     * @return Pincho[] Array con los pinchos
     * @author Sergio Malagon Martin
     */
    public function getPinchos()
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

        $pinchos = $db->getPinchos("%" . $key . "%", $order, $direction, $limit, $offset);


        foreach ($pinchos as $key => &$value) {

            $value["img"] = $db->getImgPincho($value["id"]);

            $value["puntuacion"] = $db->getPuntuacionMediaPincho($value["id"])[0]["puntuacion"] ?? 0;
        }

        $count = $db->getPinchosCount()['count'];

        http_response_code(200);

        echo json_encode(array(

            "status" => true,

            "data" => array(

                "pinchos" => $pinchos,

                "count" => $count

            )

        ));
    }

    /**
     * Obtiene un pincho
     * 
     *  - string $id -> Id del pincho [GET]
     * @return Pincho El pincho obtenido
     * @author Sergio Malagon Martin
     */
    public function getPincho()
    {

        $hayToken = true;

        $auth = new Auth();

        $token_data = $auth->getDataToken();

        if (!$token_data || !isset($token_data)) {

            $hayToken = false;
        }


        if (!isset($_GET['id'])) {

            http_response_code(404);

            echo json_encode(array(

                'status' => false,

                'message' => 'Faltan parametros'

            ));

            exit();
        }

        $idPincho = $_GET['id'];

        $db = new db\DAO();

        $pincho = $db->getPincho($idPincho);

        if (is_null($pincho) || !isset($pincho)) {

            http_response_code(200);

            echo json_encode(array(

                "status" => false,

                "message" => "El pincho no existe"

            ));

            exit();
        }

        $pincho['img'] = $db->getImgPincho($pincho["id"]);

        $pincho['resenas'] = $db->getResenasPincho($pincho["id"]);

        $pincho["puntuacion"] = $db->getPuntuacionMediaPincho($pincho["id"])[0]["puntuacion"] ?? 0;

        foreach ($pincho['resenas'] as $key => &$value) {

            $usuario = $db->getUserById($value['usuario']);

            $value['puntuacion'] = $db->getLikesResena($value['id'])[0]['count'];

            $value['user_likes'] = $token_data ?  $db->checkIfLike($token_data->id, $value['id']) : false;

            $value['usuario'] = array(
                "id" => $usuario['id'],
                "correo" => $usuario['correo'],
                "nombre_apellidos" => $usuario['nombre'] . " " . $usuario['apellidos'],
                "img" => $usuario['img']
            );
        }

        if ($hayToken) {
            $pincho["puntuacion_usuario"] = $db->getPuntuacionUsuarioPincho($token_data->id, $pincho["id"]);
        }



        http_response_code(200);

        echo json_encode(array(

            "status" => true,

            "data" => $pincho

        ));

        exit();
    }

    /**
     * Actualiza los datos de un pincho
     * 
     *  - int $id -> Id del pincho [POST]
     *  - string $nombre -> Nombre del pincho [POST]
     *  - int $puntuacion -> Puntuacion del pincho [POST]
     *  - string $ingredientes -> Ingredientes del pincho [POST]
     *  - float $precio -> Precio del pincho [POST]
     * @return null
     * @author Sergio Malagon Martin
     */
    public function updatePincho()
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

        $body_data = new Pincho(json_decode($rawdata));

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

            if ($db->updatePincho($body_data)) {

                http_response_code(201);

                echo json_encode(array(

                    "status" => true,

                    "message" => "Pincho actualizado correctamente"

                ));
            } else {

                http_response_code(400);

                echo json_encode(array(

                    "status" => false,

                    "message" => "Error actualizando el pincho"

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
     * Elimina un pincho
     * 
     *  - int $id -> Id del pincho [POST]
     * @return null
     * @author Sergio Malagon Martin
     */
    public function deletePincho()
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

        $body_data = new Pincho(json_decode($rawdata));

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

            if ($db->deletePincho($body_data)) {

                http_response_code(201);

                echo json_encode(array(

                    "status" => true,

                    "message" => "Pincho eliminado correctamente"

                ));
            } else {

                http_response_code(400);

                echo json_encode(array(

                    "status" => false,

                    "message" => "Error eliminando el pincho"

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
     * Inserta un nuevo pincho
     * 
     *  - string $nombre -> Nombre del pincho [POST]
     *  - int $puntuacion -> Puntuacion del pincho [POST]
     *  - string $ingredientes -> Ingredientes del pincho [POST]
     *  - float $precio -> Precio del pincho [POST]
     * @return null
     * @author Sergio Malagon Martin
     */
    public function insertPincho()
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

        $body_data = new Pincho(json_decode($rawdata));

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

            if ($db->insertPincho($body_data)) {

                http_response_code(201);

                echo json_encode(array(

                    "status" => true,

                    "message" => "Pincho creado correctamente"

                ));
            } else {

                http_response_code(400);

                echo json_encode(array(

                    "status" => false,

                    "message" => "Error creando el pincho"

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
     * Pone una nota a un pincho
     * 
     *  - int $pinchoId -> Id del pincho [POST]
     *  - int $usuarioId -> Id del usuario que inserta la puntuacion
     *  - int $puntuacion -> Puntuacion que se le asigna al pincho 
     * @return null
     * @author Sergio Malagon Martin
     */
    public function setNotaPincho()
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

        $body_data = json_decode($rawdata);

        if (!$body_data || !isset($body_data)) {

            http_response_code(401);

            echo json_encode(array(

                "status" => false,

                "message" => "Body not provided"

            ));

            exit();
        }

        $db = new db\DAO();

        if ($db->setNotaPincho($body_data->pincho, $token_data->id, $body_data->puntuacion)) {
            http_response_code(200);

            echo json_encode(array(

                "status" => true,

                "message" => "Puntuacion insertada"

            ));

            exit();
        }

        http_response_code(200);

        echo json_encode(array(

            "status" => false,

            "message" => "Error al insertar la puntuacion"

        ));

        exit();
    }

    /**
     * Actualiza la nota de un pincho
     * 
     *  - int $pinchoId -> Id del pincho [POST]
     *  - int $usuarioId -> Id del usuario que inserta la puntuacion
     *  - int $puntuacion -> Puntuacion que se le asigna al pincho 
     * @return null
     * @author Sergio Malagon Martin
     */
    public function updateNotaPincho()
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

        $body_data = json_decode($rawdata);

        if (!$body_data || !isset($body_data)) {

            http_response_code(401);

            echo json_encode(array(

                "status" => false,

                "message" => "Body not provided"

            ));

            exit();
        }

        $db = new db\DAO();

        if ($db->updateNotaPincho($body_data->pincho, $token_data->id, $body_data->puntuacion)) {
            http_response_code(200);

            echo json_encode(array(

                "status" => true,

                "message" => "Puntuacion actualizada"

            ));

            exit();
        }

        http_response_code(200);

        echo json_encode(array(

            "status" => false,

            "message" => "Error al actualizar la puntuacion"

        ));

        exit();
    }


    /**
     * Sube una imagen de pincho al servidor y deja registro en la BD
     * 
     *  - File $img -> Imagen del pincho [POST]
     *  - int $id -> Id del pincho [GET]
     * @return null
     * @author Sergio Malagon Martin
     */
    public function uploadImages()
    {

        if (!isset($_GET['id'])) {

            http_response_code(404);

            echo json_encode(array(

                'status' => false,

                'message' => 'Faltan parametros'

            ));

            exit();
        }

        $pinchoID = $_GET['id'];


        if (!file_exists("img/img_pinchos")) {

            mkdir("img/img_pinchos");
        }

        $target_dir = "img/img_pinchos/$pinchoID/";

        if (!file_exists($target_dir)) {

            mkdir($target_dir);
        }

        $db = new db\DAO();

        for ($i = 0; $i < COUNT($_FILES); $i++) {

            $target_file = $target_dir . basename($_FILES["file$i"]["name"]);

            if (!file_exists($target_file)) {

                move_uploaded_file($_FILES["file$i"]["tmp_name"], $target_file);

                if (!$db->insertImagenPincho($pinchoID, $_FILES["file$i"]["name"])) {

                    http_response_code(400);

                    echo json_encode(array(

                        "status" => false,

                        "message" => 'Error al insertar las imagenes'
                    ));

                    exit();
                }
            }
        }


        http_response_code(200);

        echo json_encode(array(

            "status" => true,

            "message" => 'Imagenes insertadas corretamente'
        ));

        exit();
    }

    /**
     * Elimina una imagen de pincho del servidor y elimina el registro en la BD
     * 
     *  - int $img_id -> Id de la imagen [GET]
     *  - int $pincho_id -> Id del pincho que contiene la imagen [GET]
     *  - string $filename -> Nombre de la imagen [GET]
     * @return null
     * @author Sergio Malagon Martin
     */
    public function removeImages()
    {

        if (!isset($_GET['img_id']) || !isset($_GET['pincho_id']) || !isset($_GET['filename'])) {

            http_response_code(404);

            echo json_encode(array(

                'status' => false,

                'message' => 'Faltan parametros'

            ));

            exit();
        }


        $img_id = $_GET['img_id'];

        $pincho_id = $_GET['pincho_id'];

        $filename = $_GET['filename'];

        $target_dir = "img/img_pinchos/$pincho_id/$filename";

        if (file_exists($target_dir)) {

            $db = new db\DAO();

            unlink($target_dir);

            if ($db->removeImagenPincho($img_id)) {

                http_response_code(200);

                echo json_encode(array(

                    "status" => true,

                    "message" => 'Imagenes eliminadas correctamente'
                ));

                exit();
            }
        }

        http_response_code(400);

        echo json_encode(array(

            "status" => false,

            "message" => 'Error eliminando las imagenes'
        ));

        exit();
    }


    /**
     * Devuelve un array con los 5 pinchos con mejor puntuacion por cierto usuario
     * 
     * @return Pincho[]
     * @author Sergio Malagon Martin
     */
    public function getMoreLikedPinchoByUser()
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

        $db = new db\DAO();

        $pinchos = $db->getMoreLikedPinchosByUser($token_data->id);

        http_response_code(200);

        echo json_encode(array(

            "status" => true,

            "data" => $pinchos
        ));

        exit();
    }

    /**
     * Devuelve un array con los 5 pinchos con mejor puntuacion de la web
     * 
     * @return Pincho[]
     * @author Sergio Malagon Martin
     */
    public function getMoreLikedPinchos()
    {

        $db = new db\DAO();

        $pinchos = $db->getMoreLikedPinchos();

        http_response_code(200);

        echo json_encode(array(

            "status" => true,

            "data" => $pinchos
        ));

        exit();
    }
}
