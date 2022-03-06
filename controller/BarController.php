<?php

require_once('./db/DAO.php');
require_once('./model/Bar.php');

use dao as db;

class BarController
{

    /**
     * Obtiene una lista de bares
     * 
     * Query params [GET]
     *  - int $offset -> numero de resultados que dejo fuera a partir del primero
     *  - int $limit -> numero de bares que quieres obtener
     *  - string $key -> caracteres que el bar contiene
     *  - string $order -> columna sobre la que se ordenan los bares
     *  - string $direction -> ASC o DESC
     * @return Bar[] Los bares obtenidos
     * @author Sergio Malagon Martin
     */
    public function getBares()
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

        $bares = $db->getBares("%" . $key . "%", $order, $direction, $limit, $offset);

        foreach ($bares as $key => &$value) {

            $value["img"] = $db->getImgBar($value["id"]);

            $value['pinchos'] = $db->getPinchosBar($value['id']);

            $numPinchos = 0;

            $sumaPuntuacionPinchos = 0;

            foreach ($value['pinchos'] as $key => &$pincho) {

                $sumaPuntuacionPinchos += $db->getPuntuacionMediaPincho($pincho["id"])[0]["puntuacion"];

                $numPinchos = $numPinchos + 1;
            }

            $value['media_puntuacion'] = $numPinchos !== 0 ? $sumaPuntuacionPinchos / $numPinchos : 0;
        }

        $count = $db->getBaresCount()['count'];

        http_response_code(200);

        echo json_encode(array(

            "status" => true,

            "data" => array(

                "bares" => $bares,

                "count" => $count

            )

        ));
    }


    /**
     * Obtiene un bar
     * 
     * Query params [GET]
     *  - int $id -> Id del bar
     * @return Bar El bar obtenido
     * @author Sergio Malagon Martin
     */
    public function getBar()
    {

        if (!isset($_GET['id'])) {

            http_response_code(404);

            echo json_encode(array(

                'status' => false,

                'message' => 'Faltan parametros'

            ));

            exit();
        }

        $idBar = $_GET['id'];

        $db = new db\DAO();

        $bar = $db->getBar($idBar);

        if (is_null($bar) || !isset($bar)) {

            http_response_code(200);

            echo json_encode(array(

                "status" => false,

                "message" => "El bar no existe"

            ));
            exit();
        }

        $bar["img"] = $db->getImgBar($bar["id"]);

        $bar['pinchos'] = $db->getPinchosBar($bar['id']);

        $numPinchos = 0;

        $sumaPuntuacionPinchos = 0;

        foreach ($bar['pinchos'] as $key => &$value) {

            $value["img"] = $db->getImgPincho($value["id"]);

            $value["puntuacion"] = $db->getPuntuacionMediaPincho($value["id"])[0]["puntuacion"];

            $numPinchos++;

            $sumaPuntuacionPinchos += $db->getPuntuacionMediaPincho($value["id"])[0]["puntuacion"];
        }

        $bar['media_puntuacion'] = $numPinchos !== 0 ? ($sumaPuntuacionPinchos / $numPinchos) : 0;

        http_response_code(200);

        echo json_encode(array(

            "status" => true,

            "data" => $bar

        ));
        exit();
    }

    /**
     * Actualiza los datos de un bar
     * 
     * Body data [POST]
     *  - int $id -> ID del bar
     *  - string $nombre -> Nuevo nombre
     *  - string $localizacion -> Nueva localizacacion
     *  - string $informacion -> Nueva informacion
     * @return null
     * @author Sergio Malagon Martin
     */
    public function updateBar()
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

        $body_data = new Bar(json_decode($rawdata));

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

            if ($db->updateBar($body_data)) {

                http_response_code(201);

                echo json_encode(array(

                    "status" => true,

                    "message" => "Bar actualizado correctamente"

                ));
            } else {

                http_response_code(400);

                echo json_encode(array(

                    "status" => false,

                    "message" => "Error actualizando el bar"

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
     * Elimina un bar
     * 
     * Body data [POST]
     *  - int $id -> Id del bar
     * @return null
     * @author Sergio Malagon Martin
     */
    public function deleteBar()
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

        $body_data = new Bar(json_decode($rawdata));

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

            if ($db->deleteBar($body_data)) {

                http_response_code(201);

                echo json_encode(array(

                    "status" => true,

                    "message" => "Bar eliminado correctamente"

                ));
            } else {

                http_response_code(400);

                echo json_encode(array(

                    "status" => false,

                    "message" => "Error eliminando el bar"

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
     * Inserta un bar
     * 
     * Body data [POST]
     *  - string $nombre -> Nombre del bar
     *  - string $localizacion -> Localizacacion del bar
     *  - string $informacion -> Informacion del bar
     * @return null
     * @author Sergio Malagon Martin
     */
    public function insertBar()
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

        $body_data = new Bar(json_decode($rawdata));

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

            if ($db->insertBar($body_data)) {

                http_response_code(201);

                echo json_encode(array(

                    "status" => true,

                    "message" => "Bar creado correctamente"

                ));
            } else {

                http_response_code(400);

                echo json_encode(array(

                    "status" => false,

                    "message" => "Error creando el bar"

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
     * Sube una imagen de bar al servidor y deja registro en la BD
     * 
     *  - File $img -> Imagen de bar [POST]
     *  - int $id -> Id del bar [GET]
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

        $barID = $_GET['id'];

        if (!file_exists("img/img_bares")) {

            mkdir("img/img_bares");
        }

        $target_dir = "img/img_bares/$barID/";

        if (!file_exists($target_dir)) {

            mkdir($target_dir);
        }

        $db = new db\DAO();

        for ($i = 0; $i < COUNT($_FILES); $i++) {

            $target_file = $target_dir . basename($_FILES["file$i"]["name"]);

            if (!file_exists($target_file)) {

                move_uploaded_file($_FILES["file$i"]["tmp_name"], $target_file);

                if (!$db->insertImagenBar($barID, $_FILES["file$i"]["name"])) {

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

            "message" => "Imagenes insertadas correctamente"
        ));

        exit();
    }


    /**
     * Elimina una imagen de bar del servidor y elimina el registro en la BD
     * 
     *  - int $img_id -> Id de la imagen [GET]
     *  - int $bar_id -> Id del bar que contiene la imagen [GET]
     *  - string $filename -> Nombre de la imagen [GET]
     * @return null
     * @author Sergio Malagon Martin
     */
    public function removeImages()
    {

        if (!isset($_GET['img_id']) || !isset($_GET['bar_id']) || !isset($_GET['filename'])) {

            http_response_code(404);

            echo json_encode(array(

                'status' => false,

                'message' => 'Faltan parametros'

            ));

            exit();
        }


        $img_id = $_GET['img_id'];

        $bar_id = $_GET['bar_id'];

        $filename = $_GET['filename'];

        $target_dir = "img/img_bares/$bar_id/$filename";

        if (file_exists($target_dir)) {

            $db = new db\DAO();

            unlink($target_dir);

            if ($db->removeImagenBar($img_id)) {

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
