<?php

require_once __DIR__.'\..\auxiliar\Conexion.php';
require_once __DIR__.'\..\auxiliar\Factoria.php';
require_once __DIR__.'\..\auxiliar\Constantes.php';

class ControladorAdministrador
{
    /**
     * Devuelve un jugador a partir del id.
     *
     * @param int $idJugador El id del jugador a devolver
     *
     * @return array|null
     */
    public static function getJugadorFromId($idJugador)
    {
        $jugador = Conexion::getJugadorFromId($idJugador);

        if ($jugador instanceof Jugador) {
            $cod = 200;
            $mes = 'OK';

            header('HTTP/1.1 '.$cod.' '.$mes);

            return json_encode(['Codigo' => $cod, 'Mensaje' => $mes, 'Jugador' => $jugador]);
        } else {
            $cod = 201;
            $mes = 'Jugador no encontrado';

            header('HTTP/1.1 '.$cod.' '.$mes);

            return json_encode(['Codigo' => $cod, 'Mensaje' => $mes]);
        }
    }

    /**
     * Devuelve todos los jugadores.
     *
     * @return array|null
     */
    public static function getJugadores()
    {
        $jugadores = Conexion::getJugadores();

        if ($jugadores[0] instanceof Jugador) {
            $cod = 200;
            $mes = 'OK';

            header('HTTP/1.1 '.$cod.' '.$mes);

            return json_encode(['Codigo' => $cod, 'Mensaje' => $mes, 'Jugadores' => $jugadores]);
        } else {
            $cod = 500;
            $mes = 'Error';

            header('HTTP/1.1 '.$cod.' '.$mes);

            return json_encode(['Codigo' => $cod, 'Mensaje' => $mes]);
        }
    }

    /**
     * Inserta un jugador en la base de datos.
     *
     * @param array $datosRecibidos Datos del jugador a insertar
     *
     * @return array|null
     */
    public static function insertJugador($datosRecibidos)
    {
        $jugador = Factoria::crearJugador(0,
            $datosRecibidos['user-nombre'],
            $datosRecibidos['user-email'],
            md5($datosRecibidos['user-pass']),
            0, 0, 0
        );

        if (Conexion::insertJugador($jugador)) {
            $cod = 201;
            $mes = 'Jugador insertado';

            return json_encode(['Codigo' => $cod, 'Mensaje' => $mes]);
        } else {
            $cod = 500;
            $mes = 'Error en la base de datos.';

            header('HTTP/1.1 '.$cod.' '.$mes);

            return json_encode(['Codigo' => $cod, 'Mensaje' => $mes]);
        }
    }

    /**
     * Actualiza un jugador en la base de datos.
     *
     * @param array $datosRecibidos Datos del jugador a actualizar
     *
     * @return array|null
     */
    public static function updateJugador($datosRecibidos)
    {
        $jugador = Factoria::crearJugador($datosRecibidos['user-id'], $datosRecibidos['user-nombre'], $datosRecibidos['user-email'], 0, 0, 0, 0);

        if (Conexion::updateJugador($jugador)) {
            $cod = 200;
            $mes = 'OK';

            header('HTTP/1.1 '.$cod.' '.$mes);

            return json_encode(['Codigo' => $cod, 'Mensaje' => $mes]);
        } else {
            $cod = 500;
            $mes = 'Error';

            header('HTTP/1.1 '.$cod.' '.$mes);

            return json_encode(['Codigo' => $cod, 'Mensaje' => $mes]);
        }
    }

    /**
     * Actualiza la contraseña de un jugador en la base de datos y le aplica un hash md5.
     *
     * @param int    $idJugador El id del jugador
     * @param string $pass      La nueva contraseña del jugador
     *
     * @return array|null
     */
    public static function updatePassword($idJugador, $pass)
    {
        $jugador = Factoria::crearJugador($idJugador, 0, 0, md5($pass), 0, 0, 0);

        if (Conexion::updatePassword($jugador)) {
            $cod = 200;
            $mes = 'OK';

            header('HTTP/1.1 '.$cod.' '.$mes);

            return json_encode(['Codigo' => $cod, 'Mensaje' => $mes]);
        } else {
            $cod = 500;
            $mes = 'Error';

            header('HTTP/1.1 '.$cod.' '.$mes);

            return json_encode(['Codigo' => $cod, 'Mensaje' => $mes]);
        }
    }

    /**
     * Elimina un jugador de la base de datos.
     *
     * @param int $id El id del jugador a eliminar
     *
     * @return array|null
     */
    public static function deleteJugador($id)
    {
        if (Conexion::deleteJugador($id)) {
            $cod = 200;
            $mes = 'Usuario eliminado.';

            header('HTTP/1.1 '.$cod.' '.$mes);

            return json_encode(['Codigo' => $cod, 'Mensaje' => $mes]);
        } else {
            $cod = 500;
            $mes = 'Error en la base de datos.';

            header('HTTP/1.1 '.$cod.' '.$mes);

            return json_encode(['Codigo' => $cod, 'Mensaje' => $mes]);
        }
    }
}
