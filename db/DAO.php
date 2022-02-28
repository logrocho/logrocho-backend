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
use Exception;

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

            if ($stmt->rowCount() > 0) {

                return $stmt->fetchAll(PDO::FETCH_ASSOC)[0];
            } else {

                return null;
            }
        } catch (PDOException $th) {

            echo "PDO ERROR: " . $th->getMessage();
        }
    }

    public function getUserById(int $id)
    {

        try {

            $sql = "SELECT * FROM `usuarios` WHERE id= :id";

            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(":id", $id, PDO::PARAM_INT);

            $stmt->execute();

            if ($stmt->rowCount() > 0) {

                return $stmt->fetchAll(PDO::FETCH_ASSOC)[0];
            } else {

                return null;
            }
        } catch (PDOException $th) {

            echo "PDO ERROR: " . $th->getMessage();

            return null;
        }
    }

    public function getUsers(string $key, string $order, string $direction, int $limit, int $offset)
    {

        try {


            if ($direction === "DESC") {

                $sql = "SELECT * FROM `usuarios` WHERE nombre LIKE :key ORDER BY $order DESC LIMIT :limit OFFSET :offset";
            } else if ($direction === "ASC") {

                $sql = "SELECT * FROM `usuarios` WHERE nombre LIKE :key ORDER BY $order ASC LIMIT :limit OFFSET :offset";
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


    public function getUsersCount()
    {

        try {
            $sql = "SELECT COUNT(id) as count FROM `usuarios`";

            $stmt = $this->db->prepare($sql);

            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC)[0];
        } catch (PDOException $th) {

            echo "PDO ERROR: " . $th->getMessage();
        }
    }


    public function insertUser(Usuario $user)
    {

        try {

            $sql = "INSERT INTO `usuarios` (correo, password, nombre, apellidos, rol) VALUES (:correo, SHA1(:password), :nombre, :apellidos, :rol)";

            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(":correo", $user->getCorreo(), PDO::PARAM_STR);

            $stmt->bindValue(":password", $user->getPassword(), PDO::PARAM_STR);

            $stmt->bindValue(":nombre", $user->getNombre(), PDO::PARAM_STR);

            $stmt->bindValue(":apellidos", $user->getApellidos(), PDO::PARAM_STR);

            $stmt->bindValue(":rol", "user", PDO::PARAM_STR);

            $stmt->execute();

            if ($stmt->rowCount() > 0) {

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

            $stmt->bindValue(":correo", $user->getCorreo(), PDO::PARAM_STR);

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

    public function updateUser(Usuario $user)
    {

        try {
            $sql = "UPDATE `usuarios` SET nombre= :nombre, apellidos= :apellidos WHERE correo= :correo";

            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(":correo", $user->getCorreo(), PDO::PARAM_STR);

            $stmt->bindValue(":nombre", $user->getNombre(), PDO::PARAM_STR);

            $stmt->bindValue(":apellidos", $user->getApellidos(), PDO::PARAM_STR);

            $stmt->execute();

            return true;
        } catch (PDOException $th) {

            echo "PDO ERROR: " . $th->getMessage();

            return false;
        }
    }

    public function updateUserImg($userID, $filename)
    {

        try {
            $sql = "UPDATE `usuarios` SET img= :img WHERE id= :id";

            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(":id", $userID, PDO::PARAM_INT);

            $stmt->bindValue(":img", $filename, PDO::PARAM_STR);

            $stmt->execute();

            return true;
        } catch (PDOException $th) {

            echo "PDO ERROR: " . $th->getMessage();

            return false;
        }
    }

    /// ==================
    /// Bar Controller
    /// ==================

    public function getBares(string $key, string $order, string $direction, int $limit, int $offset)
    {

        try {

            if ($direction === "DESC") {

                $sql = "SELECT * FROM `bares` WHERE nombre LIKE :key ORDER BY $order DESC LIMIT :limit OFFSET :offset";
            } else if ($direction === "ASC") {

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

    public function getBaresCount()
    {

        try {
            $sql = "SELECT COUNT(id) as count FROM `bares`";

            $stmt = $this->db->prepare($sql);

            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC)[0];
        } catch (PDOException $th) {

            echo "PDO ERROR: " . $th->getMessage();
        }
    }

    public function getImgBar(int $id)
    {
        try {

            $sql = "SELECT b_img.id, b_img.filename FROM `bares` AS b LEFT JOIN `bares_img` AS b_img ON b.id = b_img.id_bar WHERE b.id= :id";

            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(":id", $id, PDO::PARAM_INT);

            $stmt->execute();

            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($resultado[0]['filename'] === null) {

                return [];
            } else {

                return $resultado;
            }
        } catch (PDOException $th) {

            echo "PDO ERROR: " . $th->getMessage();
        }
    }

    public function getPinchosBar(int $id)
    {
        try {

            $sql = "SELECT p.id,p.nombre,p.puntuacion FROM `pinchos` as p left join `bares_pinchos` as bp on p.id = bp.pincho_id where bp.bar_id = :id ORDER BY puntuacion DESC";

            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(":id", $id, PDO::PARAM_INT);

            $stmt->execute();

            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($resultado === [null]) {

                return [];
            } else {

                return $resultado;
            }
        } catch (PDOException $th) {

            echo "PDO ERROR: " . $th->getMessage();
        }
    }

    public function getPuntuacionMediaBar(int $id)
    {
        try {

            $sql = "SELECT bares.id, bares.nombre, bares.localizacion, bares.informacion, AVG(pinchos.puntuacion) as media FROM `bares` left join bares_pinchos on bares.id = bares_pinchos.bar_id left join pinchos on bares_pinchos.pincho_id = pinchos.id WHERE bares.id = :id";

            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(":id", $id, PDO::PARAM_INT);

            $stmt->execute();

            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC)[0]['media'];

            return $resultado ?? "0";


            var_dump($stmt->fetchAll(PDO::FETCH_ASSOC)[0]['media']);
        } catch (PDOException $th) {

            echo "PDO ERROR: " . $th->getMessage();
        }
    }



    public function getBar($id)
    {
        try {
            $sql = "SELECT * FROM `bares` WHERE id= :id";

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


    public function updateBar(Bar $bar)
    {

        try {
            $sql = "UPDATE `bares` SET nombre= :nombre, localizacion= :localizacion, informacion= :informacion, latitud= :latitud, longitud= :longitud WHERE id= :id";

            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(":nombre", $bar->getNombre() ?? "", PDO::PARAM_STR);

            $stmt->bindValue(":localizacion", $bar->getLocalizacion(), PDO::PARAM_STR);

            $stmt->bindValue(":latitud", (float) $bar->getLatitud());

            $stmt->bindValue(":longitud", (float) $bar->getLongitud());

            $stmt->bindValue(":informacion", $bar->getInformacion(), PDO::PARAM_STR);

            $stmt->bindValue(":id", $bar->getId(), PDO::PARAM_STR);

            $stmt->execute();



            $sql = "DELETE FROM `bares_pinchos` WHERE bar_id= :bar_id";

            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(":bar_id", $bar->getId(), PDO::PARAM_INT);

            $stmt->execute();


            try {

                $this->db->beginTransaction();

                foreach ($bar->getPinchos() as $key => $value) {

                    $pincho_id = $value->id;

                    $bar_id = $bar->getId();

                    $sql = "INSERT INTO `bares_pinchos` (pincho_id, bar_id) VALUES($pincho_id, $bar_id)";

                    $this->db->query($sql);
                }

                $this->db->commit();
            } catch (PDOException $th) {

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
            $sql = "INSERT iNTO `bares` (nombre, localizacion, informacion,latitud,longitud) VALUES (:nombre, :localizacion, :informacion,:latitud,:longitud)";

            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(":nombre", $bar->getNombre(), PDO::PARAM_STR);

            $stmt->bindValue(":localizacion", $bar->getLocalizacion(), PDO::PARAM_STR);

            $stmt->bindValue(":informacion", $bar->getInformacion(), PDO::PARAM_STR);

            $stmt->bindValue(":latitud", (float) $bar->getLatitud());

            $stmt->bindValue(":longitud", (float) $bar->getLongitud());

            $stmt->execute();

            if ($stmt->rowCount() === 0) {

                return false;
            }

            $bar_id = $this->db->lastInsertId();

            try {

                $this->db->beginTransaction();

                foreach ($bar->getPinchos() as $key => $value) {

                    $pincho_id = $value->id;

                    $sql = "INSERT INTO `bares_pinchos` (bar_id, pincho_id) VALUES($bar_id, $pincho_id)";

                    $this->db->query($sql);
                }

                $this->db->commit();
            } catch (PDOException $th) {

                $this->db->rollBack();

                return false;
            }

            return true;
        } catch (PDOException $th) {

            echo "PDO ERROR: " . $th->getMessage();
        }
    }


    public function insertImagenBar(int $bar_id, string $filename)
    {

        try {

            $sql = "INSERT iNTO `bares_img` (id_bar, filename) VALUES (:id_bar, :filename)";

            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(":id_bar", $bar_id, PDO::PARAM_INT);

            $stmt->bindValue(":filename", $filename, PDO::PARAM_STR);

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


    public function removeImagenBar(int $img_id)
    {

        try {

            $sql = "DELETE FROM `bares_img` WHERE id= :img_id";

            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(":img_id", $img_id, PDO::PARAM_INT);

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


            if ($direction === "DESC") {

                $sql = "SELECT * FROM `pinchos` WHERE nombre LIKE :key ORDER BY $order DESC LIMIT :limit OFFSET :offset";
            } else if ($direction === "ASC") {

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

    public function getPinchosCount()
    {

        try {
            $sql = "SELECT COUNT(id) as count FROM `pinchos`";

            $stmt = $this->db->prepare($sql);

            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC)[0];
        } catch (PDOException $th) {

            echo "PDO ERROR: " . $th->getMessage();
        }
    }

    public function getResenasPincho($id)
    {

        try {
            $sql = "SELECT *  FROM `resenas` WHERE pincho = :id";

            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(":id", $id, PDO::PARAM_STR);

            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $th) {

            echo "PDO ERROR: " . $th->getMessage();
        }
    }




    public function getImgPincho(int $id)
    {
        try {

            $sql = "SELECT b_img.id, b_img.filename FROM `pinchos` AS b LEFT JOIN `pinchos_img` AS b_img ON b.id = b_img.pincho_id WHERE b.id= :id";

            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(":id", $id, PDO::PARAM_INT);

            $stmt->execute();

            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($resultado[0]['filename'] === null) {

                return [];
            } else {

                return $resultado;
            }
        } catch (PDOException $th) {

            echo "PDO ERROR: " . $th->getMessage();
        }
    }


    public function updatePincho(Pincho $pincho)
    {

        try {
            $sql = "UPDATE `pinchos` SET nombre= :nombre, puntuacion= :puntuacion, ingredientes= :ingredientes, precio= :precio WHERE id= :id";

            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(":id", $pincho->getId(), PDO::PARAM_INT);

            $stmt->bindValue(":nombre", $pincho->getNombre(), PDO::PARAM_STR);

            $stmt->bindValue(":puntuacion", $pincho->getPuntuacion(), PDO::PARAM_INT);

            $stmt->bindValue(":ingredientes", $pincho->getIngredientes(), PDO::PARAM_STR);

            $stmt->bindValue(":precio", $pincho->getPrecio(), PDO::PARAM_INT);

            $stmt->execute();

            return true;
        } catch (PDOException $th) {

            echo "PDO ERROR: " . $th->getMessage();

            return false;
        }
    }


    public function deletePincho(Pincho $pincho)
    {

        try {
            $sql = "DELETE FROM `pinchos` WHERE id= :id";

            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(":id", $pincho->getId(), PDO::PARAM_INT);

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
            $sql = "INSERT iNTO pinchos (nombre, puntuacion, ingredientes, precio) VALUES (:nombre, :puntuacion, :ingredientes, :precio)";

            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(":nombre", $pincho->getNombre(), PDO::PARAM_STR);

            $stmt->bindValue(":puntuacion", 0, PDO::PARAM_INT);

            $stmt->bindValue(":ingredientes", $pincho->getIngredientes(), PDO::PARAM_STR);

            $stmt->bindValue(":precio", $pincho->getPrecio(), PDO::PARAM_INT);

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

    public function setNotaPincho($pinchoId, $usuarioId, $puntuacion)
    {
        try {
            $sql = "UPDATE `pinchos_puntuacion` SET puntuacion= :puntuacion WHERE usuario= :usuario AND pincho= :pincho";

            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(":puntuacion", $puntuacion, PDO::PARAM_INT);

            $stmt->bindValue(":usuario", $usuarioId, PDO::PARAM_INT);

            $stmt->bindValue(":pincho", $pinchoId, PDO::PARAM_STR);

            $stmt->execute();

            return true;
        } catch (PDOException $th) {

            try {
                $sql = "INSERT INTO `pinchos_puntuacion` (usuario, pincho, puntuacion) VALUES (:usuario, :pincho, :puntuacion";

                $stmt = $this->db->prepare($sql);

                $stmt->bindValue(":usuario", $usuarioId, PDO::PARAM_INT);

                $stmt->bindValue(":pincho", $pinchoId, PDO::PARAM_INT);

                $stmt->bindValue(":puntuacion", $puntuacion, PDO::PARAM_STR);

                $stmt->execute();

                return true;
            } catch (PDOException $th) {

                echo "PDO ERROR: " . $th->getMessage();

                return false;
            }
        }
    }



    public function insertImagenPincho(int $pincho_id, string $filename)
    {
        try {

            $sql = "INSERT iNTO `pinchos_img` (pincho_id, filename) VALUES (:pincho_id, :filename)";

            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(":pincho_id", $pincho_id, PDO::PARAM_INT);

            $stmt->bindValue(":filename", $filename, PDO::PARAM_STR);

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

    public function removeImagenPincho(int $img_id)
    {

        try {

            $sql = "DELETE FROM `pinchos_img` WHERE id= :img_id";

            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(":img_id", $img_id, PDO::PARAM_INT);

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

            if ($direction === "DESC") {

                $sql = "SELECT * FROM `resenas` WHERE mensaje LIKE :key ORDER BY $order DESC LIMIT :limit OFFSET :offset";
            } else if ($direction === "ASC") {

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

    public function getResenaCount()
    {

        try {
            $sql = "SELECT COUNT(id) as count FROM `resenas`";

            $stmt = $this->db->prepare($sql);

            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC)[0];
        } catch (PDOException $th) {

            echo "PDO ERROR: " . $th->getMessage();
        }
    }


    public function updateResena(Resena $resena)
    {

        try {
            $sql = "UPDATE `resenas` SET mensaje= :mensaje WHERE id= :id";

            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(":id", $resena->getId(), PDO::PARAM_INT);

            $stmt->bindValue(":mensaje", $resena->getMensaje(), PDO::PARAM_STR);

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
            $sql = "INSERT iNTO `resenas` (usuario, pincho, mensaje, puntuacion) VALUES (:usuario, :pincho, :mensaje, :puntuacion)";

            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(":usuario", $resena->getUsuario(), PDO::PARAM_INT);

            $stmt->bindValue(":pincho", $resena->getPincho(), PDO::PARAM_INT);

            $stmt->bindValue(":mensaje", $resena->getMensaje(), PDO::PARAM_STR);

            $stmt->bindValue(":puntuacion", 0, PDO::PARAM_INT);

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
