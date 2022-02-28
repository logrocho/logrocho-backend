<?php

namespace conexion;

use PDO;
use PDOException;


class Conexion
{
    // const DB_INFO = "mysql:host=cloud.webuphosting.com;dbname=sergiomalagonoci_logrocho";
    // const DB_USER = "sergiomalagonoci_sergio";
    // const DB_PASS = "Malagon2022";

    const DB_INFO = "mysql:host=localhost;dbname=logrocho";
    const DB_USER = "root";
    const DB_PASS = "";

    /**
     * Instancia una conexion con la DB
     * @return [type] Devulve la conexion con la BD
     */
    public static function getConexion()
    {
        try {
            return new PDO(self::DB_INFO, self::DB_USER, self::DB_PASS);
        } catch (PDOException $th) {
            echo $th->getMessage();
        }
    }
}
