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
}
