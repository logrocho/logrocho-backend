<?php

class Bar
{
    private $id;

    private $nombre;

    private $localizacion;

    private $informacion;

    public function __construct($ID, $NOMBRE, $LOCALIZACION, $INFORMACION)
    {
        $this->id = $ID;

        $this->nombre = $NOMBRE;

        $this->localizacion = $LOCALIZACION;

        $this->informacion = $INFORMACION;
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
}
