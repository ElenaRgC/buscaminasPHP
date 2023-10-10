<?php

require_once '\model\Conexion.php';
require_once '\model\Controlador.php';

header('Content-Type:application/json');

$requestMethod = $_SERVER['REQUEST_METHOD'];
$paths = $_SERVER['REQUEST_URI'];
$datosRecibidos = file_get_contents('php://input');
$data = json_decode($datosRecibidos, true);

$args = explode('/', $paths);
unset($args[0]);

switch ($args[1]) {
    case 'admin' :
        switch ($requestMethod) {
            case 'GET':
                Controlador::getJugadores($datosRecibidos);
                break;
            case 'POST':
                // Añadir nuevo jugador
                break;
            case 'PUT':
                // Modificar datos jugador
                break;
            case 'DELETE':
                // Eliminar jugador
                break;
            default:
                $cod = 405;
                $mes = 'Verbo no soportado.';

                echo json_encode(['Codigo' => $cod, 'Mensaje' => $mes]);
        }
        break;

    case 'jugar':
        unset($args[1]);
        switch ($requestMethod) {
            case 'GET':
                switch (count($args)) {
                    case 0:
                        // Crear partida nueva por defecto
                        break;
                    case 2:
                        // Crear partida nueva con tamaño definido
                        break;
                    default:
                        $cod = 400;
                        $mes = 'Argumentos inválidos.';

                        echo json_encode(['Codigo' => $cod, 'Mensaje' => $mes]);
                }
                break;

            case 'POST':
                if (count($args) == 1) {
                    // Revelar una casilla
                } else {
                    $cod = 400;
                    $mes = 'Argumentos inválidos.';

                    echo json_encode(['Codigo' => $cod, 'Mensaje' => $mes]);
                }

                // no break
            default:
                $cod = 405;
                $mes = 'Verbo no soportado.';

                echo json_encode(['Codigo' => $cod, 'Mensaje' => $mes]);
        }
        break;

    case 'pass':
        if ($requestMethod == 'PUT') {
            // Solicitar contraseña nueva
        } else {
            $cod = 405;
            $mes = 'Verbo no soportado.';

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

        echo json_encode(['Codigo' => $cod, 'Mensaje' => $mes]);
}
