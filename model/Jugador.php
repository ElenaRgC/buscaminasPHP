<?php

class Jugador
{
    public $id;
    public $nombre;
    public $email;
    public $pass;
    public $partidasJugadas;
    public $partidasGanadas;
    public $esAdmin;

    public function __construct($id, $nombre, $email, $pass, $partidasJugadas, $partidasGanadas, $esAdmin)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->email = $email;
        $this->pass = $pass;
        $this->partidasJugadas = $partidasJugadas;
        $this->partidasGanadas = $partidasGanadas;
        $this->esAdmin = $esAdmin;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($value)
    {
        $this->id = $value;
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public function setNombre($value)
    {
        $this->nombre = $value;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($value)
    {
        $this->email = $value;
    }

    public function getPass()
    {
        return $this->pass;
    }

    public function setPass($value)
    {
        $this->pass = $value;
    }

    public function getPartidasJugadas()
    {
        return $this->partidasJugadas;
    }

    public function setPartidasJugadas($value)
    {
        $this->partidasJugadas = $value;
    }

    public function getPartidasGanadas()
    {
        return $this->partidasGanadas;
    }

    public function setPartidasGanadas($value)
    {
        $this->partidasGanadas = $value;
    }

    public function getEsAdmin()
    {
        return $this->esAdmin;
    }

    public function setEsAdmin($value)
    {
        $this->esAdmin = $value;
    }

    public function __toString()
    {
        return 'ID: '.$this->id.'<br>'.
               'Nombre: '.$this->nombre.'<br>'.
               'Email: '.$this->email.'<br>'.
               'Partidas Jugadas: '.$this->partidasJugadas.'<br>'.
               'Partidas Ganadas: '.$this->partidasGanadas.'<br>';
    }
}
