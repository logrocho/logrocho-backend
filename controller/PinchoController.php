<?php

require_once('./db/DAO.php');

use dao as db;

class PinchoController
{

    public function getPinchos()
    {

        $page = $_GET['page'];

        $db = new db\DAO();

        $bares = $db->getPinchos($page);

        if(is_null($bares)){

            http_response_code(400);
            
            echo json_encode(array(

                'status' => false,

                "message" => "No se han podido obtener los pinchos, comprueba que la paginacion sea correcta",

            ));

        } else {
   
            http_response_code(200);
            
            echo json_encode(array(
                
                "status" => true,
                
                "data" => $bares
                
            ));
        }
    }


    public function getPincho()
    {

        $idBar = $_GET['id'];

        $db = new db\DAO();

        $bar = $db->getPincho($idBar);

        if (is_null($bar)) {

            http_response_code(400);

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


    public function updatePincho()
    {

        $db = new db\DAO();

        $rawdata = file_get_contents("php://input");

        $datos = json_decode($rawdata);

        $auth = new Auth();

        $data = $auth->getDataToken();

        $user_rol = $db->getUser($data->email)[0]['rol'];

        if($user_rol === 'admin' && !is_null($datos)){

            if($db->updatePincho($datos)){

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

                "message" => "Token not valid or no data provided"

            ));

        }

    }


    public function deletePincho()
    {

        $db = new db\DAO();

        $rawdata = file_get_contents("php://input");

        $datos = json_decode($rawdata);

        $auth = new Auth();

        $data = $auth->getDataToken();

        $user_rol = $db->getUser($data->email)[0]['rol'];

        if($user_rol === 'admin' && !is_null($datos)){

            if($db->deletePincho($datos)){

                http_response_code(201);

                echo json_encode(array(

                    "status" => true,

                    "message" => "Pincho eliminado correctamente"

                ));

            } else {

                http_response_code(400);

                echo json_encode(array(

                    "status" => false,

                    "message" => "Error elimminando el pincho"

                ));

            }

        } else {

            http_response_code(401);

            echo json_encode(array(

                "status" => false,

                "message" => "Token not valid or no data provided"

            ));

        }

    }

    public function insertPincho()
    {

        $db = new db\DAO();

        $rawdata = file_get_contents("php://input");

        $datos = json_decode($rawdata);

        $auth = new Auth();

        $data = $auth->getDataToken();

        $user_rol = $db->getUser($data->email)[0]['rol'];

        if($user_rol === 'admin' && !is_null($datos)){

            if($db->insertPincho($datos)){

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

                "message" => "Usuario no permitido y no datos"

            ));

        }

    }
}
