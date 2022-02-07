<?php

class Resena
{

    private $id;

    private $usuario;

    private $pincho;

    private $mensaje;

    private $puntuacion;


    public function __construct($RESENA)
    {
        $this->id = $RESENA->id ?? null;

        $this->usuario = $RESENA->usuario ?? null;

        $this->pincho = $RESENA->pincho ?? null;

        $this->mensaje = $RESENA->mensaje ?? null;

        $this->puntuacion = $RESENA->puntuacion ?? null;
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

    /**
     * Get the value of pincho
     */
    public function getPincho()
    {
        return $this->pincho;
    }

    /**
     * Set the value of pincho
     *
     * @return  self
     */
    public function setPincho($pincho)
    {
        $this->pincho = $pincho;

        return $this;
    }

    /**
     * Get the value of usuario
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * Set the value of usuario
     *
     * @return  self
     */
    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;

        return $this;
    }
}
