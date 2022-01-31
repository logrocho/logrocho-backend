<?php

require_once('./db/DAO.php');
require_once('./model/Bar.php');

use dao as db;

class BarController
{

    /**
     * Obtiene una lista de bares
     * @param int $offset [Parametro GET]
     * @param int $limit [Parametro GET]
     * @param string $key [Parametro GET]
     * @param string $order [Parametro GET]
     * @param string $direction [Parametro GET]
     * @return Bar[] Los bares obtenidos
     * @author Sergio Malagon Martin
     */
    public function getBares()
    {

        if(!isset($_GET['offset']) || !isset($_GET['limit']) || !isset($_GET['key']) || !isset($_GET['order']) || !isset($_GET['direction'])){
            
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

        $bares = $db->getBares("%".$key."%", $order, $direction, $limit, $offset);

        foreach ($bares as $key => &$value) {

            $value["img"] = $db->getImgBar($value["id"]);

        }

        http_response_code(200);
            
        echo json_encode(array(
            
            "status" => true,
            
            "data" => $bares
            
        ));
    }


     /**
     * Obtiene un bar
     * @param string $id [Parametro GET]
     * @return Bar El bar obtenido
     * @author Sergio Malagon Martin
     */
    public function getBar()
    {

        if(!isset($_GET['id'])){
            
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

            http_response_code(404);

            echo json_encode(array(

                "status" => false,

                "message" => "El bar no existe"

            ));

        } else {

            http_response_code(200);

            echo json_encode(array(

                "status" => true,

                "data" => $bar

            ));
        }
    }

    /**
     * Actualiza los datos de un bar
     * @param string $id [Parametro POST]
     * @param string $nombre [Parametro POST]
     * @param string $localizacion [Parametro POST]
     * @param string $informacion [Parametro POST]
     * @param string $img [Parametro POST]
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

        $user_rol = $db->getUser($token_data->correo)[0]['rol'];

        if($user_rol === 'admin' && !is_null($body_data)){

            if($db->updateBar($body_data)){

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
     * @param string $id [Parametro GET]
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

        $user_rol = $db->getUser($token_data->correo)[0]['rol'];

        if($user_rol === 'admin' && !is_null($body_data)){

            if($db->deleteBar($body_data)){

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
     * @param string $nombre [Parametro POST]
     * @param string $localizacion [Parametro POST]
     * @param string $informacion [Parametro POST]
     * @param string $img [Parametro POST]
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

        $user_rol = $db->getUser($token_data->correo)[0]['rol'];

        if($user_rol === 'admin' && !is_null($body_data)){

            if($db->insertBar($body_data)){

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
}
