<?php

require_once('./db/BarDB.php');

use bardb as db;


class BarController
{


    public function getBares()
    {

        $db = new db\DB();

        $bares = $db->getBares();

        http_response_code(200);

        echo json_encode(array(

            "result" => true,

            "values" => $bares

        ));
    }


    public function getBar($idBar)
    {

        $db = new db\DB();

        $bar = $db->getBar($idBar);

        if (isset($bar) && !is_null($bar)) {

            http_response_code(200);

            echo json_encode(array(

                "result" => true,

                "values" => $bar

            ));
        } else {

            http_response_code(400);

            echo json_encode(array(

                "result" => false,

                "message" => "El bar no existe"

            ));
        }
    }


    public function updateBar()
    {

        $rawdata = file_get_contents("php://input");

        $datos = json_decode($rawdata);

        $auth = new Auth();

        $token = $auth->validateToken();

        if($token){

            $db = new db\DB();
    
            if($db->updateBar($datos)){
    
                http_response_code(200);
    
                echo json_encode(array(
    
                    "result" => true
    
                ));
    
            }else {
    
                http_response_code(400);
    
                echo json_encode(array(
    
                    "result" => false,
    
                    "log" => "No se ha podido actualizar la tabla, comprueba que los datos sean correctos"
    
                ));
    
            }

        } else {

            http_response_code(400);

            echo json_encode(array(

                "result" => false,

                "log" => "token not valid"

            ));
        }

    }
    
    public function deleteBar()
    {

        $rawdata = file_get_contents("php://input");

        $datos = json_decode($rawdata);

        $db = new db\DB();

        if($db->updateBar($datos)){

            http_response_code(200);

            echo json_encode(array(

                "result" => true

            ));

        }else {

            http_response_code(400);

            echo json_encode(array(

                "result" => false,

                "log" => "No se ha podido actualizar la tabla, comprueba que los datos sean correctos"

            ));

        }

    }
}
