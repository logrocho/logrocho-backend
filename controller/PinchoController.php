<?php

require_once('./db/DAO.php');
require_once('./model/Pincho.php');

use dao as db;

class PinchoController
{

    /**
     * Obtiene una lista de pinchos
     * @param int $offset [Parametro GET]
     * @param int $limit [Parametro GET]
     * @param string $key [Parametro GET]
     * @param string $order [Parametro GET]
     * @param string $direction [Parametro GET]
     * @return Pincho[] Los pinchos obtenidos
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
     * @param string $id [Parametro GET]
     * @return Pincho El pincho obtenido
     * @author Sergio Malagon Martin
     */
    public function getPincho()
    {

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

        $pincho['img'] = $db->getImgPincho($pincho["id"]);

        if (is_null($pincho) || !isset($pincho)) {

            http_response_code(404);

            echo json_encode(array(

                "status" => false,

                "message" => "El pincho no existe"

            ));
        } else {

            http_response_code(200);

            echo json_encode(array(

                "status" => true,

                "data" => $pincho

            ));
        }
    }

    /**
     * Actualiza los datos de un pincho
     * @param string $id [Parametro POST]
     * @param string $nombre [Parametro POST]
     * @param string $puntuacion [Parametro POST]
     * @param string $ingredientes [Parametro POST]
     * @param string $img [Parametro POST]
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
     * @param string $id [Parametro GET]
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
     * Inserta un pincho
     * @param string $nombre [Parametro POST]
     * @param string $puntuacion [Parametro POST]
     * @param string $ingredientes [Parametro POST]
     * @param string $img [Parametro POST]
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

            "status" => false,

            "message" => 'Imagenes insertadas corretamente'
        ));

        exit();
    }

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
}
