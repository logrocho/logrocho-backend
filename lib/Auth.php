<?php

require_once('./db/DAO.php');

use dao as db;
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\SignatureInvalidException;

class Auth
{

    private $secretKey = 'pepito';

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

                        'status' => false,

                        'message' => "jwt not found"
                    ));

                    exit;
                }
            }
        }

        http_response_code(400);

        echo json_encode(array(

            'status' => false,

            'message' => "jwt not found"

        ));

        exit;
    }

    public function getDataToken(){

        try {

            $jwt = $this->getBearerToken();

            $token_data = JWT::decode($jwt, $this->secretKey, ['HS512']);
    
            return $token_data->data;

        } catch (InvalidArgumentException $th) {
            
            http_response_code(400);

            echo json_encode(array(

                "status" => false,

                "message" => "Providad JWT was empty"
            ));

            exit();

        } catch (UnexpectedValueException $th) {

            http_response_code(400);

            echo json_encode(array(

                "status" => false,

                "message" => "Provided JWT was invalid"
            ));

            exit();

        } catch (SignatureInvalidException $th) {

            http_response_code(400);

            echo json_encode(array(

                "status" => false,

                "message" => "Provided JWT was invalid because the signature verification faile"
            ));

            exit();
            
        } catch (BeforeValidException $th) {

            http_response_code(400);

            echo json_encode(array(

                "status" => false,

                "message" => "Provided JWT is trying to be used before it's been created as defined by 'iat'"
            ));

        } catch (ExpiredException $th) {

            http_response_code(400);

            echo json_encode(array(

                "status" => false,

                "message" => "Provided JWT has since expired, as defined by the 'exp' claim"
            ));

            exit();

        }

    }

}
