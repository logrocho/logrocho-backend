<?php

class Resena
{

    private $id;

    private $id_usuario;

    private $id_pincho;

    private $mensaje;

    private $puntuacion;


    public function __construct($ID, $ID_USUARIO, $ID_PINCHO, $MENSAJE, $PUNTUACION)
    {
        $this->id = $ID;

        $this->id_usuario = $ID_USUARIO;

        $this->id_pincho = $ID_PINCHO;

        $this->mensaje = $MENSAJE;

        $this->puntuacion = $PUNTUACION;
    }

    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of id_usuario
     */
    public function getId_usuario()
    {
        return $this->id_usuario;
    }

    /**
     * Set the value of id_usuario
     *
     * @return  self
     */
    public function setId_usuario($id_usuario)
    {
        $this->id_usuario = $id_usuario;

        return $this;
    }

    /**
     * Get the value of id_pincho
     */
    public function getId_pincho()
    {
        return $this->id_pincho;
    }

    /**
     * Set the value of id_pincho
     *
     * @return  self
     */
    public function setId_pincho($id_pincho)
    {
        $this->id_pincho = $id_pincho;

        return $this;
    }

    /**
     * Get the value of mensaje
     */
    public function getMensaje()
    {
        return $this->mensaje;
    }

    /**
     * Set the value of mensaje
     *
     * @return  self
     */
    public function setMensaje($mensaje)
    {
        $this->mensaje = $mensaje;

        return $this;
    }

    /**
     * Get the value of puntuacion
     */
    public function getPuntuacion()
    {
        return $this->puntuacion;
    }

    /**
     * Set the value of puntuacion
     *
     * @return  self
     */
    public function setPuntuacion($puntuacion)
    {
        $this->puntuacion = $puntuacion;

        return $this;
    }
}
