<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once '../lib/phpMailer/Exception.php';
require_once '../lib/phpMailer/PHPMailer.php';
require_once '../lib/phpMailer/SMTP.php';

function enviarCorreo($destino, $nombre, $asunto, $body){
    $mail = new PHPMailer(true);

    try {
        //CONFIG SMTP
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'biourbiscompany@gmail.com';
        $mail->Password   = 'pbnlknscqsyihtse';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;

        //REMITENTE Y DESTINO
        $mail->setFrom('biourbiscompany@gmail.com', 'BioUrbis');
        $mail->addAddress($destino, $nombre);

        //CONTENIDO
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Subject = $asunto;
        $mail->Body    = plantillaBase($body);

        $mail->send();
        return true;

    } catch (Exception $e) {
        return false;
    }
}

function plantillaBase($contenido){
    return "
    <table style='max-width: 600px;
    padding: 10px;
    margin: auto;
    border-collapse: collapse;
    font-family: Montserrat;'>

        <tr>
            <td style='background-color:rgb(42,59,30);
            text-align:center;
            color:white;
            padding:15px;'>

                <h2>BioUrbis</h2>

                <span>
                    Gestión de Huertos Urbanos
                </span>

            </td>
        </tr>

        <tr>
            <td style='padding:20px;'>

                $contenido

                <hr>

                <p style='text-align:center;
                color:gray;
                font-size:12px;'>

                    BioUrbis <br>
                    Gestión de Huertos Urbanos <br>
                    biourbiscompany@gmail.com

                </p>

            </td>
        </tr>

    </table>
    ";
}

function correoCodigo($nombre, $codigo, $mensaje){

    return "

        <h2 style='color:rgb(184,98,12);'>
            Hola $nombre
        </h2>

        <h3>
            $mensaje
        </h3>

        <p>
            Ingrese el siguiente código:
        </p>

        <div style='text-align:center;
        font-size:30px;
        margin-top:20px;'>

            <b>$codigo</b>

        </div>
    ";
}

function correoResena($nombre, $mensajeUsuario){
    return "

        <h2 style='color:rgb(184,98,12);'>
            Hola $nombre
        </h2>

        <h3>
            Su reseña fue publicada
        </h3>

        <p>
            <strong>Mensaje:</strong>
            $mensajeUsuario
        </p>

        <p>
            Gracias por compartir su opinión.
        </p>
    ";
}

function correoSolicitud($nombre, $mensaje, $tipoSolicitud){
    return "

        <h2 style='color:rgb(184,98,12);'>
            Hola $nombre
        </h2>

        <h3>
            Solicitud enviada correctamente
        </h3>

        <p>
            <strong>Tipo:</strong>
            $tipoSolicitud
        </p>

        <p>
            <strong>Mensaje:</strong>
            $mensaje
        </p>

    ";
}