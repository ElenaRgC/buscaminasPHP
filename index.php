<?php

include_once 'auxiliar/Conexion.php';
include_once 'controllers/ControladorAdministrador.php';
include_once 'controllers/ControladorJuego.php';

header('Content-Type:application/json');

$requestMethod = $_SERVER['REQUEST_METHOD'];
$paths = $_SERVER['REQUEST_URI'];
$datosRecibidos = file_get_contents('php://input');
$data = json_decode($datosRecibidos, true);

$args = explode('/', $paths);
unset($args[0]);

$usuario = ControladorJuego::login($data);

if ($usuario instanceof Jugador) {
    switch ($args[1]) {
        case 'admin' :
            if ($usuario->getEsAdmin()) {
                switch ($requestMethod) {
                    case 'GET':
                        if (isset($data['user-id'])) {
                            echo ControladorAdministrador::getJugadorFromId($data['user-id']);
                        } else {
                            echo ControladorAdministrador::getJugadores();
                        }
                        break;
                    case 'POST':
                        echo ControladorAdministrador::insertJugador($data);
                        break;
                    case 'PUT':
                        if (isset($data['user-pass'])) {
                            echo ControladorAdministrador::updatePassword($data['user-id'], $data['user-pass']);
                        } else {
                            echo ControladorAdministrador::updateJugador($data);
                        }
                        break;
                    case 'DELETE':
                        echo ControladorAdministrador::deleteJugador($data['user-id']);
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
            $idJugador = ControladorJuego::getIdJugadorLogeado($data);
            switch ($requestMethod) {
                case 'GET':
                    switch (count($args)) {
                        case 0:
                            echo ControladorJuego::insertPartida($idJugador);
                            break;
                        case 2:
                            echo ControladorJuego::insertPartida($idJugador, $args[2], $args[3]);
                            break;
                        default:
                            $cod = 400;
                            $mes = 'Argumentos inválidos.';

                            header('HTTP/1.1 '.$cod.' '.$mes);

                            echo json_encode(['Codigo' => $cod, 'Mensaje' => $mes]);
                    }
                    break;

                case 'POST':
                    if (isset($data['casilla'])) {
                        if (isset($data['game-id'])) {
                            echo ControladorJuego::abrirCasilla($data['casilla'], $idJugador, $data['game-id']);
                        } else {
                            echo ControladorJuego::abrirCasilla($data['casilla'], $idJugador);
                        }
                    } else {
                        $cod = 400;
                        $mes = 'Argumentos inválidos.';

                        header('HTTP/1.1 '.$cod.' '.$mes);

                        echo json_encode(['Codigo' => $cod, 'Mensaje' => $mes]);
                    }

                    break;
                case 'PUT':
                    if (isset($data['fin']) && $data['fin'] == true) {
                        if (isset($data['id'])) {
                            ControladorJuego::rendirse($idJugador, $data['game-id']);
                        } else {
                            ControladorJuego::rendirse($idJugador);
                        }
                    } else {
                        $cod = 400;
                        $mes = 'Argumentos invalidos.';

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
            if ($requestMethod == 'GET') {
                echo ControladorUsuario::solicitarPassword($data);
            } else {
                $cod = 405;
                $mes = 'Verbo no soportado.';

                header('HTTP/1.1 '.$cod.' '.$mes);

                echo json_encode(['Codigo' => $cod, 'Mensaje' => $mes]);
            }
            break;

        case 'ranking':
            if ($requestMethod == 'GET') {
                echo ControladorUsuario::getRankingJugadores();
            } else {
                $cod = 405;
                $mes = 'Verbo no soportado.';

                header('HTTP/1.1 '.$cod.' '.$mes);

                echo json_encode(['Codigo' => $cod, 'Mensaje' => $mes]);
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
