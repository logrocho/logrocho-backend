<?php

include('./lib/Headers.php');

require './vendor/autoload.php';

require("./controller/UsuarioController.php");
require("./controller/BarController.php");
require("./controller/PinchoController.php");
require("./lib/Auth.php");

$userController = new UsuarioController();
$barController = new BarController();
$pinchoController = new PinchoController();
$auth = new Auth();

$home =  $_SERVER["SCRIPT_NAME"] . "/api/";

$ruta = str_replace($home, "", $_SERVER["REQUEST_URI"]);

$array_ruta = array_filter(explode("/", $ruta));

try {

    if (sizeof($array_ruta) === 0) {

        http_response_code(200);

        echo json_encode(array(

            'apiVersion' => '2022-01-17'

        ));

    } else {

        switch (explode('?', $array_ruta[0])[0]) {

            case 'login':

                $userController->login();

                break;

            case 'bares':

                if (isset($_GET['page'])) {

                    $barController->getBares();

                } else {

                    http_response_code(404);

                    echo json_encode(array(

                        'status' => false,

                        'message' => 'Endpoint inexistente'

                    ));
                }

                break;

            case 'bar':

                if (isset($_GET['id'])) {

                    $barController->getBar();

                } else {

                    http_response_code(404);

                    echo json_encode(array(

                        'status' => false,

                        'message' => 'Endpoint inexistente'

                    ));
                }

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

                if (isset($_GET['page'])) {

                    $pinchoController->getPinchos();

                } else {

                    http_response_code(404);

                    echo json_encode(array(

                        'status' => false,

                        'message' => 'Endpoint inexistente'

                    ));
                }

                break;

            case 'pincho':

                if (isset($_GET['id'])) {

                    $pinchoController->getPincho();

                } else {

                    http_response_code(404);

                    echo json_encode(array(

                        'status' => false,

                        'message' => 'Endpoint inexistente'

                    ));
                }

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