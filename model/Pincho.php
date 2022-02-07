<?php

class Pincho
{
    private $id;

    private $nombre;

    private $puntuacion;

    private $ingredientes;

    private $precio;

    private $img;


    public function __construct($PINCHO)
    {
        $this->id = $PINCHO->id ?? null;

        $this->nombre = $PINCHO->nombre ?? null;

        $this->puntuacion = $PINCHO->puntuacion ?? null;

        $this->ingredientes = $PINCHO->ingredientes ?? null;

        $this->precio = $PINCHO->precio ?? null;

        $this->img = $PINCHO->img ?? null;
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
     * Get the value of nombre
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set the value of nombre
     *
     * @return  self
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

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
     * Get the value of ingredientes
     */
    public function getIngredientes()
    {
        return $this->ingredientes;
    }

    /**
     * Set the value of ingredientes
     *
     * @return  self
     */
    public function setIngredientes($ingredientes)
    {
        $this->ingredientes = $ingredientes;

        return $this;
    }

    /**
     * Get the value of img
     */
    public function getImg()
    {
        return $this->img;
    }

    /**
     * Set the value of img
     *
     * @return  self
     */
    public function setImg($img)
    {
        $this->img = $img;

        return $this;
    }

    /**
     * Get the value of precio
     */
    public function getPrecio()
    {
        return $this->precio;
    }

    /**
     * Set the value of precio
     *
     * @return  self
     */
    public function setPrecio($precio)
    {
        $this->precio = $precio;

        return $this;
    }
}
