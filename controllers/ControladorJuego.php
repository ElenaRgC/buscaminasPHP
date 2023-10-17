<?php

require_once __DIR__.'\..\auxiliar\Conexion.php';
require_once __DIR__.'\..\auxiliar\Factoria.php';

class ControladorJuego
{
    /**
     * Realiza el proceso de login comprobando si el usuario y contraseña proporcionado coincide con los almacenados en la BBDD.
     *
     * @param array $datosRecibidos Datos del login
     *
     * @return Jugador|null
     */
    public static function login($datosRecibidos)
    {
        $jugador = Conexion::getJugadorFromEmail($datosRecibidos['email']);

        if ($jugador instanceof Jugador) {
            if ($jugador->getEmail() == $datosRecibidos['email'] && $jugador->getPass() == md5($datosRecibidos['pass'])) {
                return $jugador;
            } elseif ($jugador->getEmail() != $datosRecibidos['email'] || $jugador->getPass() != md5($datosRecibidos['pass'])) {
                $cod = 401;
                $mes = 'Usuario o contrasena incorrectos.';

                header('HTTP/1.1 '.$cod.' '.$mes);

                return json_encode(['Codigo' => $cod, 'Mensaje' => $mes]);
            }
        } else {
            $cod = 401;
            $mes = 'Usuario o contrasena incorrectos.';

            header('HTTP/1.1 '.$cod.' '.$mes);

            return json_encode(['Codigo' => $cod, 'Mensaje' => $mes]);
        }
    }

    /**
     * Obtiene el id del jugador logeado a partir del email.
     *
     * @param array $datosRecibidos Datos del login
     *
     * @return int
     */
    public static function getIdJugadorLogeado($datosRecibidos)
    {
        $jugador = Conexion::getJugadorFromEmail($datosRecibidos['email']);

        return $jugador->getId();
    }

    /**
     * Crea una nueva partida.
     *
     * @param int $idJugador El id del jugador
     * @param int $longitud  La longitud de la partida
     * @param int $bombas    La cantidad de bombas en la partida
     *
     * @return array|null
     */
    public static function insertPartida($idJugador, $longitud = 10, $bombas = 2)
    {
        $partida = Factoria::crearPartidaNueva($idJugador, $longitud, $bombas);

        if (Conexion::insertPartida($partida)) {
            $cod = 201;
            $mes = 'Partida creada';

            header('HTTP/1.1 '.$cod.' '.$mes);

            return json_encode(['Codigo' => $cod, 'Mensaje' => $mes,
            'Partida' => ControladorJuego::getPartidaReciente($idJugador)]);
        } else {
            $cod = 500;
            $mes = 'Error en la base de datos.';

            header('HTTP/1.1 '.$cod.' '.$mes);

            return json_encode(['Codigo' => $cod, 'Mensaje' => $mes]);
        }
    }

    /**
     * Obtiene las partidas abiertas para un jugador.
     *
     * @param int $idJugador El id del jugador
     *
     * @return array|int
     */
    public static function getPartidasAbiertas($idJugador)
    {
        $partidas = Conexion::getPartidasAbiertas($idJugador);

        if (isset($partidas[0])) {
            return $partidas;
        } else {
            return 0;
        }
    }

    /**
     * Devuelve una partida por su id.
     *
     * @param int $id El id de la partida
     *
     * @return Partida|int
     */
    public static function getPartidabyId($id)
    {
        $partida = Conexion::getPartidabyId($id);

        if ($partida instanceof Partida) {
            return $partida;
        } else {
            return 0;
        }
    }

    /**
     * Obtiene la ultima partida abierta del jugador.
     *
     * @param int $idJugador El id del jugador
     *
     * @return Partida|int
     */
    public static function getPartidaReciente($idJugador)
    {
        $ultimaPartida = Conexion::getPartidaReciente($idJugador);

        if ($ultimaPartida instanceof Partida) {
            return $ultimaPartida;
        } else {
            return 0;
        }
    }

    /**
     * Abre una casilla en el tablero y lo devuelve.
     *
     * @param int $casilla   La casilla a abrir
     * @param int $idJugador El id del jugador
     * @param int $idPartida El id de la partida opcional
     *
     * @return array|null
     */
    public static function abrirCasilla($casilla, $idJugador, $idPartida = 0)
    {
        if ($idPartida != 0) {
            $partida = ControladorJuego::getPartidabyId($idPartida, $idJugador);
        } else {
            $partida = ControladorJuego::getPartidaReciente($idJugador);
        }

        if ($partida instanceof Partida) {
            $tablero = $partida->getTableroSolucion();
            $tableroNuevo = $partida->getTableroJugador();
            $tableroNuevo[$casilla - 1] = $tablero[$casilla - 1];

            $partida->setTableroJugador($tableroNuevo);

            if ($tableroNuevo[$casilla - 1] == '*') {
                $partida->setFin(-1);
                ControladorJuego::finPartida($idJugador, $partida->getId(), -1);
            }

            if (substr_count($tableroNuevo, '-') == substr_count($tablero, '*')) {
                $partida->setFin(1);
                ControladorJuego::finPartida($idJugador, $partida->getId(), 1);
            }

            if (Conexion::updateTableroJugador($partida)) {
                $cod = 200;
                $mes = 'OK';

                header('HTTP/1.1 '.$cod.' '.$mes);

                return json_encode(['Codigo' => $cod, 'Mensaje' => $mes, 'Partida' => $partida]);
            } else {
                $cod = 500;
                $mes = 'Error';

                header('HTTP/1.1 '.$cod.' '.$mes);

                return json_encode(['Codigo' => $cod, 'Mensaje' => $mes]);
            }
        } else {
            $cod = 201;
            $mes = 'Partida no encontrada.';

            header('HTTP/1.1 '.$cod.' '.$mes);

            return json_encode(['Codigo' => $cod, 'Mensaje' => $mes,
            'Partidas abiertas' => ControladorJuego::getPartidasAbiertas($idJugador)]);
        }
    }

    /**
     * Cambia el estado de finalización de una partida en la BBDD y actualiza las estadísticas del jugador.
     *
     * @param int $idJugador El id del jugador
     * @param int $idPartida El id de la partida
     * @param int $resultado El resultado de la partida
     */
    public static function finPartida($idJugador, $idPartida, $resultado)
    {
        Conexion::closePartida($idPartida, $resultado);

        if ($resultado = -1) {
            $resultado = 0;
        }
        Conexion::updateStatsJugador($idJugador, $resultado);
    }

    /**
     * Comprueba si hay partidas abiertas del jugador y llama a finPartida().
     *
     * @param int      $idJugador El id del jugador
     * @param int|null $idPartida El id de la partida opcional
     *
     * @return array|null
     */
    public static function rendirse($idJugador, $idPartida = null)
    {
        if ($idPartida == null) {
            $partida = ControladorJuego::getPartidaReciente($idJugador);
            if ($partida instanceof Partida) {
                $idPartida = $partida->getId();
                ControladorJuego::finPartida($idJugador, $idPartida, -1);

                $cod = 200;
                $mes = 'Partida cerrada.';

                header('HTTP/1.1 '.$cod.' '.$mes);

                return json_encode(['Codigo' => $cod, 'Mensaje' => $mes, 'Partida' => $partida]);
            } else {
                $cod = 201;
                $mes = 'No hay partidas abiertas.';

                header('HTTP/1.1 '.$cod.' '.$mes);

                return json_encode(['Codigo' => $cod, 'Mensaje' => $mes]);
            }
        }
    }
}
