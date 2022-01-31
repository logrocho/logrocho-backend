<?php

require_once('./db/DAO.php');
require_once('./model/Resena.php');

use dao as db;

class ResenaController
{

     /**
     * Obtiene una lista de reseñas
     * @param int $offset [Parametro GET]
     * @param int $limit [Parametro GET]
     * @param string $key [Parametro GET]
     * @param string $order [Parametro GET]
     * @param string $direction [Parametro GET]
     * @return Resena[] Las reseñas obtenidos
     * @author Sergio Malagon Martin
     */
    public function getResenas() // TODO: Modificar metodo para obtener todos los mensajes por cada usuario
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

        $resenas = $db->getResenas("%".$key."%", $order, $direction, $limit, $offset);

        http_response_code(200);
            
        echo json_encode(array(
            
            "status" => true,
            
            "data" => $resenas
            
        ));
    }

    /**
     * Obtiene una reseña
     * @param string $id [Parametro GET]
     * @return Resena El reseña obtenido
     * @author Sergio Malagon Martin
     */
    public function getResena()
    {

        if(!isset($_GET['id'])){
            
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

        if(is_null($resena) || !isset($resena)){

            http_response_code(404);

            echo json_encode(array(

                "status" => false,

                "message" => "La reseña no existe"

            ));

        } else {

            http_response_code(200);

            echo json_encode(array(

                "status" => true,

                "data" => $resena

            ));
        }
    }

    /**
     * Actualiza los datos de una reseña
     * @param string $id [Parametro POST]
     * @param string $id_usuario [Parametro POST]
     * @param string $id_pincho [Parametro POST]
     * @param string $mensaje [Parametro POST]
     * @param string $puntuacion [Parametro POST]
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

        $user_rol = $db->getUser($token_data->correo)[0]['rol'];

        if($user_rol === 'admin' && !is_null($body_data)){

            if($db->updateResena($body_data)){

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
     * @param string $id [Parametro GET]
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

        $user_rol = $db->getUser($token_data->correo)[0]['rol'];

        if($user_rol === 'admin' && !is_null($body_data)){

            if($db->deleteResena($body_data)){

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
     * @param string $id_usuario [Parametro POST]
     * @param string $id_pincho [Parametro POST]
     * @param string $mensaje [Parametro POST]
     * @param string $puntuacion [Parametro POST]
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

        $user_rol = $db->getUser($token_data->correo)[0]['rol'];

        if($user_rol === 'admin' && !is_null($body_data)){

            if($db->insertResena($body_data)){

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

                "message" => "Accion exclusiva de usuarios admin"

            ));

        }

    }
}
