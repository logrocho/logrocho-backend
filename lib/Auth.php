<?php

require_once('./db/DAO.php');

use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\SignatureInvalidException;

class Auth
{

    private $secretKey = 'pepito';

    private function getAuthorizationHeader()

    {

        $headers = null;

        if (isset($_SERVER['Authorization'])) {

            $headers = trim($_SERVER["Authorization"]);

        } elseif (isset($_SERVER['HTTP_AUTHORIZATION'])) {

            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);

        } elseif (function_exists('apache_request_headers')) {

            $requestHeaders = apache_request_headers();

            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));

            if (isset($requestHeaders['Authorization'])) {

                $headers = trim($requestHeaders['Authorization']);

            }

        }

        return $headers;

    }


    public function getBearerToken()

    {

        $headers = $this->getAuthorizationHeader();

        if (!empty($headers) && preg_match('/Bearer\s(\S+)/', $headers, $matches)) {

            return $matches[1];

        }

        return null;

    }

    public function getDataToken(){

        try {

            $jwt = $this->getBearerToken();

            $token_data = JWT::decode($jwt, new Key($this->secretKey, 'HS512'));
    
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

            exit();

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
