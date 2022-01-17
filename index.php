<?php

include('./lib/Headers.php');

//Incluyo los archivos necesarios
require './vendor/autoload.php';

require("./controller/UsuarioController.php");
require("./controller/BarController.php");
require("./lib/Auth.php");

//Instancia el controlador
$userController = new UsuarioController();
$barController = new BarController();
$auth = new Auth();

//Ruta de la home
$home =  $_SERVER["SCRIPT_NAME"] . "/api/";

//Quito la home de la ruta de la barra de direcciones
$ruta = str_replace($home, "", $_SERVER["REQUEST_URI"]);

//Creo el array de ruta (filtrando los vacios)
$array_ruta = array_filter(explode("/", $ruta));

try {

    if (sizeof($array_ruta) === 0) {

        http_response_code(200);

        echo json_encode(array(

            'apiVersion' => '2022-01-17'

        ));

    } else {

        switch ($array_ruta[0]) {

            case 'login':

                $userController->login();

                break;

            case 'verifyAuth':

                $auth->validateToken();

                break;

            case 'bares':

                $barController->getBares();

                break;
            
            case 'bar':

                if(isset($array_ruta[1])){

                    $barController->getBar($array_ruta[1]);

                } else {
    
                    http_response_code(400);

                    echo json_encode(array(

                        'log' => 'Endpoint inexistente'

                    ));
                    
                }

                break;
            case 'updateBar':

                $barController->updateBar();

                break;

            default:

                http_response_code(400);

                echo json_encode(array(

                    'log' => 'Endpoint inexistente'

                ));

                break;
        }
    }
} catch (Exception $th) {
    http_response_code(400);

    echo json_encode(array(

        "log" => "Error al procesar la peticion, intentalo mas tarde!!"

    ));
}






//Decido la ruta en funcion de los elementos del array

// if (isset($array_ruta[0]) && $array_ruta[0] === "login") {

//     $userController->login();
// }



// if (isset($array_ruta[0]) && $array_ruta[0] === "check_login") {

//     $userController->validateToken();
// }




// if (isset($array_ruta[0]) && $array_ruta[0] === "categorias_no_token") {
//     $api->getCategoriasNoToken();
// }

// if (isset($array_ruta[0]) && $array_ruta[0] === "login") {
//     $api->getTokenSimple();
// }

// if (isset($array_ruta[0]) && $array_ruta[0] === "categorias_simple") {
//     $api->getCategoriasSimple();
// }

// if (isset($array_ruta[0]) && $array_ruta[0] === "loginJWT") {
//     $api->getTokenJWT();
// }

// if (isset($array_ruta[0]) && $array_ruta[0] === "categorias_jwt") {
//     $api->getCategoriasJWT();
// }

// if (isset($array_ruta[0]) && $array_ruta[0] === "registro_usuario") {
//     $api->registrarUsuario();
// }


// if (isset($array_ruta[0]) && $array_ruta[0] === "productos") {

//     if (isset($array_ruta[1])) {

//         $api->getProductosSimple($array_ruta[1]);
//     }
// }

// if (isset($array_ruta[0]) && $array_ruta[0] === "productos_jwt") {

//     if (isset($array_ruta[1])) {

//         $api->getProductosJWT($array_ruta[1]);
//     }
// }

// if (isset($array_ruta[0]) && $array_ruta[0] === "pedidos") {

//     if (isset($array_ruta[1])) {

//         $api->getPedidosSimple($array_ruta[1]);
//     }
// }

// if (isset($array_ruta[0]) && $array_ruta[0] === "pedidos_jwt") {

//     if (isset($array_ruta[1])) {

//         $api->getPedidosJWT($array_ruta[1]);
//     }
// }
