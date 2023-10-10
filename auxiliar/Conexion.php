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

    public static function getJugadorFromEmail($datosRecibidos)
    {
        self::$conexion = self::conectar();

        $query = 'SELECT * FROM jugador WHERE email = ?';

        $stmt = self::$conexion->prepare($query);
        $stmt->bind_param('s', $datosRecibidos['email']);

        try {
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
}
