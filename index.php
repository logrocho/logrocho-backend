<?php

include('./lib/Headers.php');

require './vendor/autoload.php';

require("./controller/UsuarioController.php");
require("./controller/BarController.php");
require("./controller/PinchoController.php");
require("./controller/ResenaController.php");
require("./lib/Auth.php");

$userController = new UsuarioController();
$barController = new BarController();
$pinchoController = new PinchoController();
$resenaController = new ResenaController();
$auth = new Auth();

$home =  $_SERVER["SCRIPT_NAME"] . "/api/";

$ruta = str_replace($home, "", $_SERVER["REQUEST_URI"]);

$array_ruta = array_filter(explode("/", $ruta));

try {

    if (sizeof($array_ruta) === 0) {

        http_response_code(200);

        echo json_encode(array(

            'apiVersion' => '2022-01-31'

        ));

    } else {

        switch (explode('?', $array_ruta[0])[0]) {

            case 'login':

                $userController->login();

                break;

            case 'insertUser':

                $userController->insertUser();
    
                break;

            case 'deleteUser':

                $userController->deleteUser();
        
                break;
            
            case 'updateUser':

                $userController->updateUser();

                break;

            case 'users':

                $userController->getUsers();

                break;
            
            case 'user':

                $userController->getUser();

                break;

            case 'bares':

                $barController->getBares();

                break;

            case 'bar':

                $barController->getBar();

                break;

            case 'updateBar':

                $barController->updateBar();

                break;

            case 'insertBar':

                $barController->insertBar();

                break;

            case 'deleteBar':

                $barController->deleteBar();

                break;

            case 'pinchos':

                $pinchoController->getPinchos();

                break;

            case 'pincho':

                $pinchoController->getPincho();

                break;
            case 'updatePincho':

                $pinchoController->updatePincho();

                break;

            case 'insertPincho':

                $pinchoController->insertPincho();

                break;

            case 'deletePincho':

                $pinchoController->deletePincho();

                break;

            case 'resenas':

                $resenaController->getResenas();

                break;

            case 'resena':

                $resenaController->getResena();

                break;

            case 'updateResena':

                $resenaController->updateResena();

                break;

            case 'deleteResena':

                $resenaController->deleteResena();

                break;
            
            case 'insertResena':

                $resenaController->insertResena();

                break;

            default:

                http_response_code(404);

                echo json_encode(array(

                    'status' => false,

                    'message' => 'Endpoint inexistente'

                ));

                break;
        }
    }
} catch (Exception $th) {

    http_response_code(500);

    echo json_encode(array(

        "status" => false,

        "message" => "Error al procesar la peticion, intentalo mas tarde!!"

    ));
}