<?php
class Partida {
    public $id;
    public $idJugador;
    public $tableroSolucion;
    public $tableroJugador;
    public $fin;

	public function __constructor($id, $idJugador, $tableroSolucion, $tableroJugador, $fin) {

		$this->id = $id;
		$this->idJugador = $idJugador;
		$this->tableroSolucion = $tableroSolucion;
		$this->tableroJugador = $tableroJugador;
		$this->fin = $fin;
	}

	public function getId() {
		return $this->id;
	}

	public function setId($value) {
		$this->id = $value;
	}

	public function getIdJugador() {
		return $this->idJugador;
	}

	public function setIdJugador($value) {
		$this->idJugador = $value;
	}

	public function getTableroSolucion() {
		return $this->tableroSolucion;
	}

	public function setTableroSolucion($value) {
		$this->tableroSolucion = $value;
	}

	public function getTableroJugador() {
		return $this->tableroJugador;
	}

	public function setTableroJugador($value) {
		$this->tableroJugador = $value;
	}

	public function getFin() {
		return $this->fin;
	}

	public function setFin($value) {
		$this->fin = $value;
	}
}