<?php
//Incluyo los archivos necesarios
require './vendor/autoload.php';

require("./controller/UsuarioController.php");

//Instancia el controlador
$userController = new UsuarioController();

//Ruta de la home
$home =  $_SERVER["SCRIPT_NAME"] . "/api/";

//Quito la home de la ruta de la barra de direcciones
$ruta = str_replace($home, "", $_SERVER["REQUEST_URI"]);

//Creo el array de ruta (filtrando los vacios)
$array_ruta = array_filter(explode("/", $ruta));



//Decido la ruta en funcion de los elementos del array

if (isset($array_ruta[0]) && $array_ruta[0] === "login") {

    $userController->login();

}



if (isset($array_ruta[0]) && $array_ruta[0] === "check_login") {

    $userController->validateToken();

}




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
