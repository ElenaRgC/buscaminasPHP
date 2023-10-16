<?php

require_once 'Constantes.php';
require_once 'Factoria.php';

class Conexion
{
    public static $conexion;

    public static function conectar()
    {
        try {
            self::$conexion = new mysqli(
                Constantes::$URL,
                Constantes::$USER,
                Constantes::$PASSWORD,
                Constantes::$DATABASE_NAME
            );

            return self::$conexion;
        } catch (Exception $e) {
            return 0;
        }
    }

    public static function desconectar()
    {
        self::$conexion->close();
    }

    // JUGADOR -------------------------------------

    public static function getJugadores()
    {
        self::$conexion = self::conectar();

        $query = 'SELECT * FROM jugador';

        $stmt = self::$conexion->prepare($query);

        try {
            $stmt->execute();
            $resultados = $stmt->get_result();
            $jugadores = [];

            while ($fila = $resultados->fetch_array()) {
                $j = Factoria::crearJugador($fila[0], $fila[1], $fila[2], $fila[3], $fila[4], $fila[5], $fila[6]);
                $jugadores[] = $j;
            }

            $resultados->free_result();

            return $jugadores;
        } catch (Exception $e) {
            return 0;
        } finally {
            self::desconectar();
        }
    }

    public static function getJugadorFromEmail($email)
    {
        self::$conexion = self::conectar();

        $query = 'SELECT * FROM jugador WHERE email = ?';

        $stmt = self::$conexion->prepare($query);

        try {
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $resultados = $stmt->get_result();

            while ($fila = $resultados->fetch_array()) {
                $j = Factoria::crearJugador($fila[0], $fila[1], $fila[2], $fila[3], $fila[4], $fila[5], $fila[6]);
            }

            $resultados->free_result();

            return $j;
        } catch (Exception $e) {
            return 0;
        } finally {
            self::desconectar();
        }
    }

    public static function insertJugador($jugador)
    {
        self::$conexion = self::conectar();

        $query = 'INSERT INTO jugador (nombre, email, pass) VALUES (?,?,?)';

        $stmt = self::$conexion->prepare($query);

        $nombre = $jugador->getNombre();
        $email = $jugador->getEmail();
        $pass = $jugador->getPass();

        try {
            $stmt->bind_param('sss', $nombre, $email, $pass);
            $stmt->execute();

            return 1;
        } catch (Exception $e) {
            return 0;
        }
    }

    public static function updateJugador($jugador)
    {
        self::$conexion = self::conectar();

        $query = 'UPDATE jugador SET nombre = ?, email = ? WHERE id = ?';

        $stmt = self::$conexion->prepare($query);

        $nombre = $jugador->getNombre();
        $email = $jugador->getEmail();
        $id = $jugador->getId();

        try {
            $stmt->bind_param('ssi', $nombre, $email, $id);
            $stmt->execute();

            return 1;
        } catch (Exception $e) {
            return 0;
        } finally {
            self::desconectar();
        }
    }

    public static function updatePassword($jugador)
    {
        self::$conexion = self::conectar();

        $query = 'UPDATE jugador SET pass = ? WHERE id = ?';

        $stmt = self::$conexion->prepare($query);

        $pass = $jugador->getPass();
        $id = $jugador->getId();

        try {
            $stmt->bind_param('si', $pass, $id);
            $stmt->execute();

            return 1;
        } catch (Exception $e) {
            return 0;
        } finally {
            self::desconectar();
        }
    }

    public static function deleteJugador($id)
    {
        self::$conexion = self::conectar();

        $query = 'DELETE FROM jugador WHERE id = ?';

        $stmt = self::$conexion->prepare($query);

        try {
            $stmt->bind_param('i', $id);
            $stmt->execute();

            return true;
        } catch (Exception $e) {
            return false;
        } finally {
            self::desconectar();
        }
    }

    // PARTIDA -------------------------------------

    public static function insertPartida($partida)
    {
        self::$conexion = self::conectar();

        $query = 'INSERT INTO partida (idJugador, tableroSolucion, tableroJugador, fin) VALUES (?, ?, ?, ?);';

        $stmt = self::$conexion->prepare($query);

        $idJugador = $partida->getIdJugador();
        $tableroSolucion = $partida->getTableroSolucion();
        $tableroJugador = $partida->getTableroJugador();
        $fin = $partida->getFin();

        try {
            $stmt->bind_param('issi', $idJugador, $tableroSolucion, $tableroJugador, $fin);
            $stmt->execute();

            return 1;
        } catch (Exception $e) {
            return 0;
        }
    }

    public static function getPartidabyId($id)
    {
        self::$conexion = self::conectar();

        $query = 'SELECT * FROM partida WHERE id = ?';

        $stmt = self::$conexion->prepare($query);

        try {
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $resultados = $stmt->get_result();

            $p = 0;

            while ($fila = $resultados->fetch_array()) {
                $p = Factoria::crearPartida(
                    $fila['id'],
                    $fila['idJugador'],
                    $fila['tableroSolucion'],
                    $fila['tableroJugador'],
                    $fila['fin']);
            }

            $resultados->free_result();

            return $p;
        } catch (Exception $e) {
            return 0;
        } finally {
            self::desconectar();
        }
    }

    public static function getPartidasAbiertas($idJugador)
    {
        self::$conexion = self::conectar();

        $query = 'SELECT * FROM partida WHERE fin = 0 AND idJugador = ?';

        $stmt = self::$conexion->prepare($query);

        try {
            $stmt->bind_param('i', $idJugador);
            $stmt->execute();
            $result = $stmt->get_result();

            $partidas = [];

            while ($fila = $result->fetch_assoc()) {
                $p = Factoria::crearPartida(
                    $fila['id'],
                    $fila['idJugador'],
                    $fila['tableroSolucion'],
                    $fila['tableroJugador'],
                    $fila['fin']);

                $partidas[] = $p;
            }

            return $partidas;
        } catch (Exception $e) {
            return 0;
        } finally {
            self::desconectar();
        }
    }

    public static function getPartidaReciente($idJugador)
    {
        self::$conexion = self::conectar();

        $query = 'SELECT * FROM partida WHERE fin = 0 AND idJugador = ? ORDER BY id DESC LIMIT 1';

        $stmt = self::$conexion->prepare($query);

        try {
            $stmt->bind_param('i', $idJugador);
            $stmt->execute();
            $result = $stmt->get_result();

            $p = 0;

            while ($fila = $result->fetch_assoc()) {
                $p = Factoria::crearPartida(
                    $fila['id'],
                    $fila['idJugador'],
                    $fila['tableroSolucion'],
                    $fila['tableroJugador'],
                    $fila['fin']);
            }

            return $p;
        } catch (Exception $e) {
            return 0;
        } finally {
            self::desconectar();
        }
    }

    public static function updateTableroJugador($partida)
    {
        self::$conexion = self::conectar();

        $query = 'UPDATE partida SET tableroJugador = ? WHERE id = ?';

        $stmt = self::$conexion->prepare($query);

        $tableroJugador = $partida->getTableroJugador();
        $id = $partida->getId();

        try {
            $stmt->bind_param('si', $tableroJugador, $id);
            $stmt->execute();

            return 1;
        } catch (Exception $e) {
            return 0;
        } finally {
            self::desconectar();
        }
    }
}
