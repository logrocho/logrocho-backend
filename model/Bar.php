<?php

class Bar
{
    private $id;

    private $nombre;

    private $localizacion;

    private $latitud;

    private $longitud;

    private $informacion;

    private $img;

    private $pinchos;

    public function __construct($BAR)
    {
        $this->id = $BAR->id ?? null;

        $this->nombre = $BAR->nombre ?? null;

        $this->localizacion = $BAR->localizacion ?? null;

        $this->latitud = $BAR->latitud ?? null;

        $this->longitud = $BAR->longitud ?? null;

        $this->informacion = $BAR->informacion ?? null;

        $this->img = $BAR->img ?? null;

        $this->pinchos = $BAR->pinchos ?? null;
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

    /**
     * Get the value of pinchos
     */ 
    public function getPinchos()
    {
        return $this->pinchos;
    }

    /**
     * Set the value of pinchos
     *
     * @return  self
     */ 
    public function setPinchos($pinchos)
    {
        $this->pinchos = $pinchos;

        return $this;
    }

    /**
     * Get the value of latitud
     */ 
    public function getLatitud()
    {
        return $this->latitud;
    }

    /**
     * Set the value of latitud
     *
     * @return  self
     */ 
    public function setLatitud($latitud)
    {
        $this->latitud = $latitud;

        return $this;
    }

    /**
     * Get the value of longitud
     */ 
    public function getLongitud()
    {
        return $this->longitud;
    }

    /**
     * Set the value of longitud
     *
     * @return  self
     */ 
    public function setLongitud($longitud)
    {
        $this->longitud = $longitud;

        return $this;
    }
}
