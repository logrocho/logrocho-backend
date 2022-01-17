<?php

namespace bardb;


require_once('Conexion.php');
require_once("./model/Bar.php");

use Bar;
use PDOException;
use PDO;

use conexion as con;

class DB
{
    private $db;

    public function __construct()
    {

        $this->db = con\Conexion::getConexion();
    }

    public function getBares()
    {

        try {
            $sql = "SELECT * FROM bares";

            $stmt = $this->db->prepare($sql);

            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                
                $resultado =  $stmt->fetchAll(PDO::FETCH_ASSOC);

                return $resultado;

            } else {

                return false;

            }

        } catch (PDOException $th) {

            echo "PDO ERROR: " . $th->getMessage();

        }
    }


    public function getBar($id)
    {

        try {
            $sql = "SELECT * FROM bares WHERE id= :id";

            $stmt = $this->db->prepare($sql);

            $stmt->execute(array(

                "id" => $id
                
            ));

            if ($stmt->rowCount() > 0) {
                
                $resultado =  $stmt->fetch(PDO::FETCH_ASSOC);

                return $resultado;

            } else {

                return null;

            }

        } catch (PDOException $th) {

            echo "PDO ERROR: " . $th->getMessage();

        }
    }


    public function updateBar($bar)
    {

        try {
            $sql = "UPDATE bares SET nombre= :nombre, localizacion= :localizacion, informacion= :informacion WHERE id= :id";

            $stmt = $this->db->prepare($sql);

            $stmt->execute(array(

                "nombre" => $bar->nombre,

                "localizacion" => $bar->localizacion,

                "infomacion" => $bar->informacion,
                
                "id" => $bar->id,
            ));

            if ($stmt->rowCount() > 0) {

                return true;

            } else {

                return null;

            }

        } catch (PDOException $th) {

            echo "PDO ERROR: " . $th->getMessage();

        }
    }
}