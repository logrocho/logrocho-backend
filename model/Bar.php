<?php

class Bar
{
    private $id;

    private $nombre;

    private $localizacion;

    private $informacion;

    private $img;

    public function __construct($BAR)
    {
        $this->id = $BAR->id ?? null;

        $this->nombre = $BAR->nombre ?? null;

        $this->localizacion = $BAR->localizacion ?? null;

        $this->informacion = $BAR->informacion ?? null;

        $this->img = $BAR->img ?? null;
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
     * Get the value of localizacion
     */
    public function getLocalizacion()
    {
        return $this->localizacion;
    }

    /**
     * Set the value of localizacion
     *
     * @return  self
     */
    public function setLocalizacion($localizacion)
    {
        $this->localizacion = $localizacion;

        return $this;
    }

    /**
     * Get the value of informacion
     */
    public function getInformacion()
    {
        return $this->informacion;
    }

    /**
     * Set the value of informacion
     *
     * @return  self
     */
    public function setInformacion($informacion)
    {
        $this->informacion = $informacion;

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
}
