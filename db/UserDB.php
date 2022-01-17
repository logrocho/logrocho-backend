<?php

namespace userdatabase;


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


    public function login($correo, $password)
    {

        try {

            $sql = "SELECT * FROM `usuario` WHERE correo= :correo AND password= SHA1(:password)";

            $stmt = $this->db->prepare($sql);

            $stmt->execute(array(

                "correo" => $correo,

                "password" => $password

            ));

            if ($stmt->rowCount() > 0) {

                return true;

            } else {

                return false;

            }
        } catch (PDOException $th) {

            echo "PDO ERROR: " . $th->getMessage();
        }
    }


    public function getUser($correo)
    {

        try {

            $sql = "SELECT * FROM `usuario` WHERE correo= :correo";

            $stmt = $this->db->prepare($sql);

            $stmt->execute(array(

                "correo" => $correo,

            ));

            if($stmt->rowCount()>0){

                $user = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
                return $user;

            } else {

                return false;

            }

        } catch (PDOException $th) {

            echo "PDO ERROR: " . $th->getMessage();
        }
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






    // public function getRestaurante($correo, $clave)
    // {
    //     try {
    //         $sql = "SELECT * FROM RESTAURANTE WHERE CORREO='$correo' AND CLAVE='$clave'";
    //         $resultado = $this->db->query($sql);
    //         if($resultado->rowCount()>0){
    //             return $resultado;
    //         }else{
    //             print_r($this->db->errorInfo());
    //             return false;
    //         }
    //     } catch (PDOException $th) {
    //         echo "PDO ERROR: " . $th->getMessage();
    //     }
    // }

    // public function getCategorias()
    // {
    //     $salida = [];
    //     try {
    //         $sql = "SELECT * FROM CATEGORIA";
    //         $resultado = $this->db->query($sql);
    //         if ($resultado->rowCount() > 0) {
    //             foreach ($resultado as $categoria) {
    //                 array_push($salida, array(
    //                     "codCat" => $categoria["CodCat"],
    //                     "nombre" => $categoria["Nombre"],
    //                     "descripcion" => $categoria["Descripcion"],
    //                 ));
    //             }
    //             return $salida;
    //         } else {
    //             print_r($this->db->errorInfo());
    //             return false;
    //         }
    //     } catch (PDOException $th) {
    //         echo "PDO ERROR: " . $th->getMessage();
    //     }
    // }

    // public function getProductos($categoria)
    // {
    //     $salida = [];
    //     try {
    //         $sql = "SELECT * FROM PRODUCTO WHERE SHA1(CODCAT)='$categoria'";
    //         $resultado = $this->db->query($sql);
    //         if ($resultado->rowCount() > 0) {
    //             foreach ($resultado as $producto) {
    //                 array_push($salida, array(
    //                     "codProd" => $producto["CodProd"],
    //                     "codCat" => $producto["CodCat"],
    //                     "nombre" => $producto["Nombre"],
    //                     "descripcion" => $producto["Descripcion"],
    //                     "peso" => $producto["Peso"],
    //                     "stock" => $producto["Stock"]
    //                 ));
    //             }
    //             return $salida;
    //         } else {
    //             print_r($this->db->errorInfo());
    //             return false;
    //         }
    //     } catch (PDOException $th) {
    //         echo "PDO ERROR: " . $th->getMessage();
    //     }
    // }

    // public function getDetallePedidos($pk_restaurante)
    // {
    //     $salida = [];
    //     try {
    //         $sql = "SELECT pedidosproductos.id,pedidosproductos.CodPed,pedidosproductos.CodProd,pedidosproductos.unidades
    //         FROM pedidosproductos
    //         INNER JOIN pedido ON pedidosproductos.CodPed = pedido.CodPed 
    //         WHERE pedido.CodRes = '$pk_restaurante'";
    //         $resultado = $this->db->query($sql);
    //         if ($resultado->rowCount() > 0) {
    //             foreach ($resultado as $key) {
    //                 array_push($salida, array(
    //                     "id" => $key["id"],
    //                     "codPed" => $key["CodPed"],
    //                     "codProd" => $key["CodProd"],
    //                     "unidades" => $key["unidades"]
    //                 ));
    //             }
    //             return $salida;
    //         } else {
    //             print_r($this->db->errorInfo());
    //             return false;
    //         }
    //     } catch (PDOException $th) {
    //         echo "PDO ERROR: " . $th->getMessage();
    //     }
    // }

    // public function registrar($correo, $clave, $direccion)
    // {
    //     try {
    //         $sql = "INSERT INTO RESTAURANTE (CORREO,CLAVE,DIRECCION) VALUES ('$correo','$clave','$direccion')";
    //         $resultado = $this->db->query($sql);
    //         if ($resultado->rowCount() > 0) {
    //             return true;
    //         } else {
    //             print_r($this->db->errorInfo());
    //             return false;
    //         }
    //     } catch (PDOException $th) {
    //         echo "PDO ERROR: " . $th->getMessage();
    //     }
    // }
}
