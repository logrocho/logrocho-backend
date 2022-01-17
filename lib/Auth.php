<?php

require_once('./db/UserDB.php');

use userdatabase as db;

use Firebase\JWT\JWT;

class Auth
{


    /**
     * Get header Authorization
     * */
    private function getAuthorizationHeader()
    {
        $headers = null;
        if (isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER["Authorization"]);
        } else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        } elseif (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();
            // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
            //print_r($requestHeaders);
            if (isset($requestHeaders['Authorization'])) {
                $headers = trim($requestHeaders['Authorization']);
            }
        }
        return $headers;
    }


    /**
     * Get access token from header
     * */
    private function getBearerToken()
    {
        $headers = $this->getAuthorizationHeader();

        if (!empty($headers)) {

            if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {

                $jwt = $matches[1];

                if ($jwt) {

                    return $jwt;

                } else {

                    http_response_code(400);

                    echo json_encode(array(

                        'result' => false,

                        'message' => "jwt not found"
                    ));

                    exit;
                }
            }
        }

        http_response_code(400);

        echo json_encode(array(

            'result' => false,

            'message' => "jwt not found"

        ));

        exit;
    }


    public function validateToken()
    {

        try {

            $secretKey = 'pepito';

            $jwt = $this->getBearerToken();

            $token = JWT::decode($jwt, $secretKey, ['HS512']);

            $now = new DateTimeImmutable();

            if ($token->nbf > $now->getTimestamp() || $token->exp < $now->getTimestamp()) {

                http_response_code(400);

                echo json_encode(array(

                    'result' => false,

                    'message' => "jwt not valid"

                ));
            } else {

                $db = new db\DB();

                $user = $db->getUser($token->data->email);

                if(isset($user) && !is_null($user)){

                    $user_data = array(

                        "email" => $user[0]["correo"],

                        "nombre" => $user[0]["nombre"],

                        "apellidos" => $user[0]["apellidos"],

                        "rol" => $user[0]["rol"]

                    );

                    http_response_code(200);

                    echo json_encode(array(

                        'result' => true,

                        "data" => $user_data

                    ));
                    
                } else {

                    http_response_code(400);

                    echo json_encode(array(

                        'result' => false,

                        'message' => "jwt not valid"

                    ));
                }
            }
        } catch (\Exception $th) {

            http_response_code(400);

            echo json_encode(array(

                'result' => false,

                'message' => "jwt not valid"

            ));
        }
    }
}
