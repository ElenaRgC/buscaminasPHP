<?php

use PHPMailer\PHPMailer\PHPMailer;

require_once __DIR__.'\..\phpmailer\src\Exception.php';
require_once __DIR__.'\..\phpmailer\src\PHPMailer.php';
require_once __DIR__.'\..\phpmailer\src\SMTP.php';
require_once __DIR__.'\..\auxiliar\Conexion.php';
require_once __DIR__.'\..\auxiliar\Factoria.php';
require_once __DIR__.'\..\auxiliar\Constantes.php';
include_once 'ControladorAdministrador.php';
include_once 'ControladorJuego.php';

class ControladorUsuario
{
    /**
     * Devuelve la clasificación de los jugadores por victorias.
     *
     * @return array|null
     */
    public static function getRankingJugadores()
    {
        $jugadores = Conexion::getRankingJugadores();

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
     * Solicita un cambio de contraseña con PHPMailer.
     *
     * @param array $datosRecibidos Los datos recibidos del frontend
     *
     * @return array|null
     */
    public static function solicitarPassword($datosRecibidos)
    {
        $id = ControladorJuego::getIdJugadorLogeado($datosRecibidos);
        $jugador = ControladorAdministrador::getJugadorFromId($id);

        $newPassword = rand(1000, 9999);

        try {
            $mail = new PHPMailer();
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = Constantes::$USERNAME;
            $mail->Password = Constantes::$PASS;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;

            // Emisor
            $mail->setFrom(Constantes::$USERNAME, 'Buscaminas');

            // Destinatarios
            if ($jugador instanceof Jugador) {
                $mail->addAddress($jugador->getEmail(), $jugador->getNombre());
            } else {
                $mail->addAddress($datosRecibidos['email'], 'Estimado jugador');
            }

            $mail->isHTML(true);
            $mail->Subject = 'Solicitud de cambio de contraseña.';
            $mail->Body = 'Su nueva contraseña es: <b>'.$newPassword.'</b>.';
            $mail->AltBody = 'Su nueva contraseña es: '.$newPassword.'.';

            ControladorAdministrador::updatePassword($id, $newPassword);

            $mail->send();

            $cod = 200;
            $mes = 'Mensaje enviado.';
            header('HTTP/1.1 '.$cod.' '.$mes);

            return json_encode(['Codigo' => $cod, 'Mensaje' => $mes]);
            echo 'El mensaje ha sido enviado';
        } catch (Exception $e) {
            $cod = 500;
            $mes = 'Error';

            header('HTTP/1.1 '.$cod.' '.$mes);

            return json_encode(['Codigo' => $cod, 'Mensaje' => $mes]);
        }
    }
}
