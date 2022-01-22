<?php

require_once('./db/DAO.php');

use dao as db;

class BarController
{

    public function getBares()
    {

        $page = $_GET['page'];

        $db = new db\DAO();

        $bares = $db->getBares($page);

        if(is_null($bares)){

            http_response_code(400);
            
            echo json_encode(array(

                'status' => false,

                "message" => "No se han podido obtener los bares, comprueba que la paginacion sea correcta",

            ));

        } else {
   
            http_response_code(200);
            
            echo json_encode(array(
                
                "status" => true,
                
                "data" => $bares
                
            ));
        }
    }


    public function getBar()
    {

        $idBar = $_GET['id'];

        $db = new db\DAO();

        $bar = $db->getBar($idBar);

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


    public function updateBar()
    {

        $db = new db\DAO();

        $rawdata = file_get_contents("php://input");

        $datos = json_decode($rawdata);

        $auth = new Auth();

        $data = $auth->getDataToken();

        $user_rol = $db->getUser($data->email)[0]['rol'];

        if($user_rol === 'admin' && !is_null($datos)){


            if($db->updateBar($datos)){

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

                "message" => "Token not valid or no data provided"

            ));

        }

    }


    public function deleteBar()
    {

        $db = new db\DAO();

        $rawdata = file_get_contents("php://input");

        $datos = json_decode($rawdata);

        $auth = new Auth();

        $data = $auth->getDataToken();

        $user_rol = $db->getUser($data->email)[0]['rol'];

        if($user_rol === 'admin' && !is_null($datos)){

            if($db->deleteBar($datos)){

                http_response_code(201);

                echo json_encode(array(

                    "status" => true,

                    "message" => "Bar eliminado correctamente"

                ));

            } else {

                http_response_code(400);

                echo json_encode(array(

                    "status" => false,

                    "message" => "Error elimminando el bar"

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

    public function insertBar()
    {

        $db = new db\DAO();

        $rawdata = file_get_contents("php://input");

        $datos = json_decode($rawdata);

        $auth = new Auth();

        $data = $auth->getDataToken();

        $user_rol = $db->getUser($data->email)[0]['rol'];

        if($user_rol === 'admin' && !is_null($datos)){

            if($db->insertBar($datos)){

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

                "message" => "Token not valid or no data provided"

            ));

        }

    }
}
