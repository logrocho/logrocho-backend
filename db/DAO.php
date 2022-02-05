<?php

namespace dao;


require_once('Conexion.php');

require_once("./model/Bar.php");

require_once("./model/Pincho.php");

require_once('./model/Resena.php');

use Pincho;
use Resena;
use Usuario;
use Bar;
use PDOException;
use PDO;

use conexion as con;

class DAO
{
    private $db;

    public function __construct()
    {

        $this->db = con\Conexion::getConexion();
    }



    /// ==================
    /// User Controller
    /// ==================

    public function login(string $correo, string $password)
    {

        try {

            $sql = "SELECT * FROM `usuarios` WHERE correo= :correo AND password= SHA1(:password)";

            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(":correo", $correo, PDO::PARAM_STR);

            $stmt->bindValue(":password", $password, PDO::PARAM_STR);

            $stmt->execute();

            if ($stmt->rowCount() > 0) {

                return true;

            } else {

                return false;

            }
        } catch (PDOException $th) {

            echo "PDO ERROR: " . $th->getMessage();
        }
    }


    public function getUser(string $correo)
    {

        try {

            $sql = "SELECT * FROM `usuarios` WHERE correo= :correo";

            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(":correo", $correo, PDO::PARAM_STR);

            $stmt->execute();

            if($stmt->rowCount()>0){

                return $stmt->fetchAll(PDO::FETCH_ASSOC)[0];

            } else {

                return null;

            }

        } catch (PDOException $th) {

            echo "PDO ERROR: " . $th->getMessage();
        }
    }


    public function getUsers(string $key, string $order, string $direction, int $limit, int $offset)
    {

        try {

            
            if($direction === "DESC"){

                $sql = "SELECT * FROM `usuarios` WHERE nombre LIKE :key ORDER BY $order DESC LIMIT :limit OFFSET :offset";

            } else if ($direction === "ASC"){

                $sql = "SELECT * FROM `usuarios` WHERE nombre LIKE :key ORDER BY $order ASC LIMIT :limit OFFSET :offset";

            }

            $stmt = $this->db->prepare($sql);
            
            $stmt->bindValue(":key",$key, PDO::PARAM_STR );

            $stmt->bindValue(":limit", $limit, PDO::PARAM_INT);

            $stmt->bindValue(":offset", $offset, PDO::PARAM_INT);

            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);


            // if($resultado->rowCount()>0){

    
            // } else {

            //     return null;

            // }

        } catch (PDOException $th) {

            echo "PDO ERROR: " . $th->getMessage();
        }
    }


    public function insertUser(Usuario $user)
    {

        try {

            $sql = "INSERT INTO `usuarios` (correo, password, nombre, apellidos, img) VALUES (:correo, SHA1(:password), :nombre, :apellidos, :img)";

            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(":correo", $user->getCorreo(), PDO::PARAM_STR);

            $stmt->bindValue(":password", $user->getPassword(), PDO::PARAM_STR);

            $stmt->bindValue(":nombre", $user->getNombre(), PDO::PARAM_STR);

            $stmt->bindValue(":apellidos", $user->getApellidos(), PDO::PARAM_STR);

            $stmt->bindValue(":img", $user->getImg(), PDO::PARAM_STR);

            $stmt->execute();

            if($stmt->rowCount()>0){
    
                return true;

            } else {

                return null;

            }

        } catch (PDOException $th) {

            echo "PDO ERROR: " . $th->getMessage();
        }
    }

    public function deleteUser(Usuario $user)
    {

        try {
            $sql = "DELETE FROM `usuarios` WHERE correo= :correo";

            $stmt = $this->db->prepare($sql);

            $stmt->execute(array(

                "correo" => $user->getCorreo(),
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

    public function updateUser(Usuario $user)
    {

        try {
            $sql = "UPDATE `usuarios` SET password= SHA1(:password), nombre= :nombre, apellidos= :apellidos, img= :img, rol= :rol WHERE correo= :correo";

            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(":correo", $user->getCorreo(), PDO::PARAM_STR);

            $stmt->bindValue(":password", $user->getPassword(), PDO::PARAM_STR);

            $stmt->bindValue(":nombre", $user->getNombre(), PDO::PARAM_STR);

            $stmt->bindValue(":apellidos", $user->getApellidos(), PDO::PARAM_STR);

            $stmt->bindValue(":img", $user->getImg(), PDO::PARAM_STR);

            $stmt->bindValue(":rol", $user->getRol() ?? 'user', PDO::PARAM_STR);

            $stmt->execute();

            if ($stmt->rowCount() > 0) {

                return true;

            } else {

                return false;

            }

        } catch (PDOException $th) {

            echo "PDO ERROR: " . $th->getMessage();

        }
    }
    







    /// ==================
    /// Bar Controller
    /// ==================

    //SELECT b_img.img FROM `bares` as b left join bares_img as b_img on b.id = b_img.id_bar WHERE b.id = 1;

    public function getBares(string $key, string $order, string $direction, int $limit, int $offset)
    {

        try {

            if($direction === "DESC"){

                $sql = "SELECT * FROM `bares` WHERE nombre LIKE :key ORDER BY $order DESC LIMIT :limit OFFSET :offset";

            } else if ($direction === "ASC"){

                $sql = "SELECT * FROM `bares` WHERE nombre LIKE :key ORDER BY $order ASC LIMIT :limit OFFSET :offset";

            }

            $stmt = $this->db->prepare($sql);
                
            $stmt->bindValue(":key", $key, PDO::PARAM_STR);

            $stmt->bindValue(":limit", $limit, PDO::PARAM_INT);

            $stmt->bindValue(":offset", $offset, PDO::PARAM_INT);

            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $th) {

            echo "PDO ERROR: " . $th->getMessage();

        }
    }

    public function getBaresCount(){

        try {
            $sql = "SELECT COUNT(id) as count FROM `bares`";

            $stmt = $this->db->prepare($sql);

            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC)[0];

        } catch(PDOException $th){
            
            echo "PDO ERROR: " . $th->getMessage();

        }
    }

    public function getImgBar(int $id){
        try {

            $sql = "SELECT b_img.img FROM `bares` AS b LEFT JOIN `bares_img` AS b_img ON b.id = b_img.id_bar WHERE b.id= :id";

            $stmt = $this->db->prepare($sql);
                
            $stmt->bindValue(":id", $id, PDO::PARAM_INT);

            $stmt->execute();

            $resultado = $stmt->fetchAll(PDO::FETCH_COLUMN);

            if($resultado === [null]){

                return [];

            } else {

                return $resultado;

            }
        } catch (PDOException $th) {

            echo "PDO ERROR: " . $th->getMessage();

        }
    }

    public function getPinchosBar(int $id){
        try {

            $sql = "SELECT p.id,p.nombre,p.puntuacion,p.ingredientes FROM `pinchos` as p left join `bares_pinchos` as bp on p.id = bp.pincho_id where bp.bar_id = :id";

            $stmt = $this->db->prepare($sql);
                
            $stmt->bindValue(":id", $id, PDO::PARAM_INT);

            $stmt->execute();

            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if($resultado === [null]){

                return [];

            } else {

                return $resultado;

            }
        } catch (PDOException $th) {

            echo "PDO ERROR: " . $th->getMessage();

        }
    }


    public function getBar(int $id)
    {

        try {
            $sql = "SELECT * FROM `bares` WHERE id= :id";

            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(":id", $id, PDO::PARAM_INT);

            $stmt->execute();

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


    public function updateBar(Bar $bar)
    {

        try {
            $sql = "UPDATE `bares` SET nombre= :nombre, localizacion= :localizacion, informacion= :informacion WHERE id= :id";

            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(":nombre",$bar->getNombre() ?? "", PDO::PARAM_STR);

            $stmt->bindValue(":localizacion",$bar->getLocalizacion(), PDO::PARAM_STR);

            $stmt->bindValue(":informacion",$bar->getInformacion(), PDO::PARAM_STR);

            $stmt->bindValue(":id",$bar->getId(), PDO::PARAM_STR);

            $stmt->execute();



            $sql = "DELETE FROM `bares_pinchos` WHERE bar_id= :bar_id";

            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(":bar_id", $bar->getId(),PDO::PARAM_INT);

            $stmt->execute();


            try {

                $this->db->beginTransaction();

            foreach ($bar->getPinchos() as $key => $value) {

                $pincho_id = $value->id;

                $bar_id = $bar->getId();

                $sql = "INSERT INTO `bares_pinchos` (pincho_id, bar_id) VALUES($pincho_id, $bar_id)";

                $this->db->query($sql);

            }

            $resultado = $this->db->commit();

            } catch (\Throwable $th) {

                $this->db->rollBack();

                return false;

            }

            return true;

        } catch (PDOException $th) {

            echo "PDO ERROR: " . $th->getMessage();

            return false;

        }
    }


    public function deleteBar(Bar $bar)
    {

        try {
            $sql = "DELETE FROM `bares` WHERE id= :id";

            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(":id", $bar->getId(), PDO::PARAM_INT);

            $stmt->execute();

            if ($stmt->rowCount() > 0) {

                return true;

            } else {

                return false;

            }

        } catch (PDOException $th) {

            echo "PDO ERROR: " . $th->getMessage();

        }
    }


    public function insertBar(Bar $bar)
    {

        try {
            $sql = "INSERT iNTO bares (nombre, localizacion, informacion, img) VALUES (:nombre, :localizacion, :informacion, :img)";

            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(":nombre", $bar->getNombre(), PDO::PARAM_STR);

            $stmt->bindValue(":localizacion", $bar->getLocalizacion(), PDO::PARAM_STR);

            $stmt->bindValue(":informacion", $bar->getInformacion(), PDO::PARAM_STR);
            
            $stmt->bindValue(":img", $bar->getImg(), PDO::PARAM_STR);
            
            $stmt->execute();

            if ($stmt->rowCount() > 0) {

                return true;

            } else {

                return false;

            }

        } catch (PDOException $th) {

            echo "PDO ERROR: " . $th->getMessage();

        }
    }




    /// ==================
    /// Pincho Controller
    /// ==================

    public function getPinchos(string $key, string $order, string $direction, int $limit, int $offset)
    {

        try {


            if($direction === "DESC"){

                $sql = "SELECT * FROM `pinchos` WHERE nombre LIKE :key ORDER BY $order DESC LIMIT :limit OFFSET :offset";

            } else if ($direction === "ASC"){

                $sql = "SELECT * FROM `pinchos` WHERE nombre LIKE :key ORDER BY $order ASC LIMIT :limit OFFSET :offset";

            }

            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(":key", $key, PDO::PARAM_STR);

            $stmt->bindValue(":limit", $limit, PDO::PARAM_INT);

            $stmt->bindValue(":offset", $offset, PDO::PARAM_INT);

            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $th) {

            echo "PDO ERROR: " . $th->getMessage();

        }
    }


    public function getPincho(int $id)
    {

        try {
            $sql = "SELECT * FROM `pinchos` WHERE id= :id";

            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(":id", $id, PDO::PARAM_INT);

            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                
                return $stmt->fetch(PDO::FETCH_ASSOC);

            } else {

                return null;

            }

        } catch (PDOException $th) {

            echo "PDO ERROR: " . $th->getMessage();

        }
    }


    public function updatePincho(Pincho $pincho)
    {

        try {
            $sql = "UPDATE `pinchos` SET nombre= :nombre, puntuacion= :puntuacion, ingredientes= :ingredientes, img= :img WHERE id= :id";

            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(":id", $pincho->getId(), PDO::PARAM_INT);

            $stmt->bindValue(":nombre", $pincho->getNombre(), PDO::PARAM_STR);

            $stmt->bindValue(":puntuacion", $pincho->getPuntuacion(), PDO::PARAM_INT);

            $stmt->bindValue(":ingredientes", $pincho->getIngredientes(), PDO::PARAM_STR);

            $stmt->bindValue(":img", $pincho->getImg(), PDO::PARAM_STR);

            $stmt->execute();

            if ($stmt->rowCount() > 0) {

                return true;

            } else {

                return false;

            }

        } catch (PDOException $th) {

            echo "PDO ERROR: " . $th->getMessage();

        }
    }


    public function deletePincho(Pincho $pincho)
    {

        try {
            $sql = "DELETE FROM pinchos WHERE id= :id";

            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(":id", $pincho->getId(), PDO::PARAM_INT );

            $stmt->execute();

            if ($stmt->rowCount() > 0) {

                return true;

            } else {

                return false;

            }

        } catch (PDOException $th) {

            echo "PDO ERROR: " . $th->getMessage();

        }
    }


    public function insertPincho(Pincho $pincho)
    {

        try {
            $sql = "INSERT iNTO pinchos (nombre, puntuacion, ingredientes, img) VALUES (:nombre, :puntuacion, :ingredientes, :img)";

            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(":nombre", $pincho->getNombre(), PDO::PARAM_STR);

            $stmt->bindValue(":puntuacion", $pincho->getPuntuacion(), PDO::PARAM_INT);

            $stmt->bindValue(":ingredientes", $pincho->getIngredientes(), PDO::PARAM_STR);

            $stmt->bindValue(":img", $pincho->getImg(), PDO::PARAM_STR);

            $stmt->execute();

            if ($stmt->rowCount() > 0) {

                return true;

            } else {

                return false;

            }

        } catch (PDOException $th) {

            echo "PDO ERROR: " . $th->getMessage();

        }
    }



    /// ==================
    /// Resena Controller
    /// ==================

    public function getResenas(string $key, string $order, string $direction, int $limit, int $offset)
    {

        try {

            if($direction === "DESC"){

                $sql = "SELECT * FROM `resenas` WHERE mensaje LIKE :key ORDER BY $order DESC LIMIT :limit OFFSET :offset";

            } else if ($direction === "ASC"){

                $sql = "SELECT * FROM `resenas` WHERE mensaje LIKE :key ORDER BY $order ASC LIMIT :limit OFFSET :offset";

            }

            $stmt = $this->db->prepare($sql);

            $stmt->bindParam(":key", $key, PDO::PARAM_STR);

            $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);

            $stmt->bindParam(":offset", $offset, PDO::PARAM_INT);

            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $th) {

            echo "PDO ERROR: " . $th->getMessage();

        }
    }


    public function getResena(int $id)
    {

        try {
            $sql = "SELECT * FROM `resenas` WHERE id= :id";

            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(":id", $id, PDO::PARAM_INT);

            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                
                return $stmt->fetch(PDO::FETCH_ASSOC);

            } else {

                return null;

            }

        } catch (PDOException $th) {

            echo "PDO ERROR: " . $th->getMessage();

        }
    }


    public function updateResena(Resena $resena)
    {

        try {
            $sql = "UPDATE `resenas` SET id_usuario= :id_usuario, id_pincho= :id_pincho, mensaje= :mensaje, puntuacion= :puntuacion WHERE id= :id";

            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(":id", $resena->getId(), PDO::PARAM_INT);

            $stmt->bindValue(":id_usuario", $resena->getId_usuario(), PDO::PARAM_INT);

            $stmt->bindValue(":id_pincho", $resena->getId_pincho(), PDO::PARAM_INT);

            $stmt->bindValue(":mensaje", $resena->getMensaje(), PDO::PARAM_STR);

            $stmt->bindValue(":puntuacion", $resena->getPuntuacion(), PDO::PARAM_INT);

            $stmt->execute();

            if ($stmt->rowCount() > 0) {

                return true;

            } else {

                return false;

            }

        } catch (PDOException $th) {

            echo "PDO ERROR: " . $th->getMessage();

        }
    }


    public function deleteResena(Resena $resena)
    {

        try {
            $sql = "DELETE FROM `resenas` WHERE id= :id";

            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(":id", $resena->getId(), PDO::PARAM_INT);

            $stmt->execute();

            if ($stmt->rowCount() > 0) {

                return true;

            } else {

                return false;

            }

        } catch (PDOException $th) {

            echo "PDO ERROR: " . $th->getMessage();

        }
    }


    public function insertResena(Resena $resena)
    {

        try {
            $sql = "INSERT iNTO `resenas` (id_usuario, id_pincho, mensaje, puntuacion) VALUES (:id_usuario, :id_pincho, :mensaje, :puntuacion)";

            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(":id_usuario", $resena->getId_usuario(), PDO::PARAM_INT);

            $stmt->bindValue(":id_pincho", $resena->getId_pincho(), PDO::PARAM_INT);

            $stmt->bindValue(":mensaje", $resena->getMensaje(), PDO::PARAM_STR);

            $stmt->bindValue(":puntuacion", $resena->getPuntuacion(), PDO::PARAM_INT);

            $stmt->execute();

            if ($stmt->rowCount() > 0) {

                return true;

            } else {

                return false;

            }

        } catch (PDOException $th) {

            echo "PDO ERROR: " . $th->getMessage();

        }
    }


}