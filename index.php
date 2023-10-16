<?php

include_once 'auxiliar/Conexion.php';
include_once 'controllers/ControladorJugador.php';
include_once 'controllers/ControladorPartida.php';

header('Content-Type:application/json');

$requestMethod = $_SERVER['REQUEST_METHOD'];
$paths = $_SERVER['REQUEST_URI'];
$datosRecibidos = file_get_contents('php://input');
$data = json_decode($datosRecibidos, true);

$args = explode('/', $paths);
unset($args[0]);

$usuario = ControladorJugador::login($data);

if ($usuario instanceof Jugador) {
    switch ($args[1]) {
        case 'admin' :
            if ($usuario->getEsAdmin()) {
                switch ($requestMethod) {
                    case 'GET':
                        echo ControladorJugador::getJugadores();
                        break;
                    case 'POST':
                        echo ControladorJugador::insertJugador($data);
                        break;
                    case 'PUT':
                        if (isset($data['user-pass'])) {
                            echo ControladorJugador::updatePassword($data);
                        } else {
                            echo ControladorJugador::updateJugador($data);
                        }
                        break;
                    case 'DELETE':
                        echo ControladorJugador::deleteJugador($data['id']);
                        break;
                    default:
                        $cod = 405;
                        $mes = 'Verbo no soportado.';

                        header('HTTP/1.1 '.$cod.' '.$mes);

                        echo json_encode(['Codigo' => $cod, 'Mensaje' => $mes]);
                }
                break;
            } else {
                $cod = 401;
                $mes = 'No autorizado.';

                header('HTTP/1.1 '.$cod.' '.$mes);

                echo json_encode(['Codigo' => $cod, 'Mensaje' => $mes]);
            }
            break;

        case 'jugar':
            unset($args[1]);
            $idJugador = ControladorJugador::getIdJugadorLogeado($data);
            switch ($requestMethod) {
                case 'GET':
                    switch (count($args)) {
                        case 0:
                            echo ControladorPartida::insertPartida($idJugador);
                            break;
                        case 2:
                            echo ControladorPartida::insertPartida($idJugador, $args[2], $args[3]);
                            break;
                        default:
                            $cod = 400;
                            $mes = 'Argumentos inválidos.';

                            header('HTTP/1.1 '.$cod.' '.$mes);

                            echo json_encode(['Codigo' => $cod, 'Mensaje' => $mes]);
                    }
                    break;

                case 'POST':
                    if (count($args) == 0) {
                        if (isset($data['id'])) {
                            echo ControladorPartida::getPartidabyId($data['id'], $idJugador);
                        } else {
                            echo ControladorPartida::getPartidaReciente($idJugador);
                        }
                    } else {
                        $cod = 400;
                        $mes = 'Argumentos inválidos.';

                        header('HTTP/1.1 '.$cod.' '.$mes);

                        echo json_encode(['Codigo' => $cod, 'Mensaje' => $mes]);
                    }

                    break;
                default:
                    $cod = 405;
                    $mes = 'Verbo no soportado.';

                    header('HTTP/1.1 '.$cod.' '.$mes);

                    echo json_encode(['Codigo' => $cod, 'Mensaje' => $mes]);
            }
            break;

        case 'pass':
            if ($requestMethod == 'PUT') {
                // Solicitar contraseña nueva
            } else {
                $cod = 405;
                $mes = 'Verbo no soportado.';

                header('HTTP/1.1 '.$cod.' '.$mes);

                echo json_encode(['Codigo' => $cod, 'Mensaje' => $mes]);
            }
            break;

        case 'ranking':
            if ($requestMethod == 'GET') {
                // Mostar nombres de usuario ordenados por número de victorias
            }
            break;

        default:
            $cod = 400;
            $mes = 'Argumentos inválidos.';

            header('HTTP/1.1 '.$cod.' '.$mes);

            echo json_encode(['Codigo' => $cod, 'Mensaje' => $mes]);
    }
} else {
    echo $usuario;
}
